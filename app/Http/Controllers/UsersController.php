<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Traits\Queries;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller {
    use ApiResponser;
    use Queries;

    public function index( $id = null ) {
        if ( $id ) {
            // 🔹 Fetch single user
            $user = DB::table( 'users' )->where( 'id', $id )->first();

            if ( !$user ) {
                return $this->set_response( null, 404, 'error', [ 'User not found' ] );
            }

            // 🔹 Fetch roles assigned to this user
            $roles = DB::table( 'model_has_roles' )
            ->join( 'roles', 'model_has_roles.role_id', '=', 'roles.id' )
            ->where( 'model_has_roles.model_type', 'App\Models\User' )
            ->where( 'model_has_roles.model_id', $id )
            ->pluck( 'roles.name' )
            ->toArray();

            // 🔹 Role info ( id, name, permissions )
            $role_info = DB::table( 'roles' )
            ->leftJoin( 'role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id' )
            ->leftJoin( 'permissions', 'role_has_permissions.permission_id', '=', 'permissions.id' )
            ->whereIn( 'roles.name', $roles )
            ->select(
                'roles.id as role_id',
                'roles.name as role_name',
                DB::raw( 'GROUP_CONCAT(permissions.name) as permissions' )
            )
            ->groupBy( 'roles.id', 'roles.name' )
            ->get();

            // 🔹 Attach role_info to user
            $user->role_info = $role_info;

            // 🔹 Add application and portal role details
 

            $data = [
                'user' => $user,
                'roles' => $roles,

            ];

            return $this->set_response( $data, 200, 'success', [ 'Single user data' ] );
        }

        // 🔹 Fetch all users
        $users = DB::table( 'users' )->get();

        // 🔹 Loop through users to attach role, app, and portal info
        foreach ( $users as $user ) {
            // Roles assigned to user
            $roles = DB::table( 'model_has_roles' )
            ->join( 'roles', 'model_has_roles.role_id', '=', 'roles.id' )
            ->where( 'model_has_roles.model_type', 'App\Models\User' )
            ->where( 'model_has_roles.model_id', $user->id )
            ->pluck( 'roles.name' )
            ->toArray();

            // Role info ( id, name, permissions )
            $role_info = DB::table( 'roles' )
            ->leftJoin( 'role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id' )
            ->leftJoin( 'permissions', 'role_has_permissions.permission_id', '=', 'permissions.id' )
            ->whereIn( 'roles.name', $roles )
            ->select(
                'roles.id as role_id',
                'roles.name as role_name',
                DB::raw( 'GROUP_CONCAT(permissions.name) as permissions' )
            )
            ->groupBy( 'roles.id', 'roles.name' )
            ->get();

            // Attach details to user object
            $user->roles = $roles;
            $user->role_info = $role_info;

         
            $user->app_name = $app->name ?? null;
            $user->portal_role = $portal_role->role_name ?? null;
        }

        // 🔹 Return unified structure
        return $this->set_response( $users, 200, 'success', [ 'All users list' ] );
    }

    // Get all users

    public function getAllUsers( Request $request ) {
        $users = DB::table( 'users' )->select('id', 'name', 'email')->orderBy( 'name' )->get();

        return $this->set_response( $users, 200, 'success', [ 'All Users data' ] );
    }

    // Create new user
public function createUser(Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'role_ids' => 'required|array|min:1',
        'department' => 'nullable|string', // Add validation for department
        'status' => 'nullable|integer|in:0,1', // Add validation for status
    ]);

    if ($validator->fails()) {
        return $this->set_response(null, 422, 'error', $validator->errors()->all());
    }

    DB::beginTransaction();
    try {
        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'department' => $request->department ?? null, // Add department
            'status' => $request->status ?? 1, // Add status (default to active)
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($request->role_ids as $roleId) {
            DB::table('model_has_roles')->insert([
                'role_id' => $roleId,
                'model_type' => 'App\Models\User',
                'model_id' => $userId,
            ]);
        }

        DB::commit();
        return $this->set_response(['id' => $userId], 200, 'success', ['User created successfully']);
    } catch (\Exception $e) {
        DB::rollBack();
        return $this->set_response(null, 422, 'error', ['Something went wrong: ' . $e->getMessage()]);
    }
}

public function updateUser(Request $request)
{
    $validator = Validator::make($request->all(), [
        'id' => 'required|exists:users,id',
        'name' => 'sometimes|string',
        'email' => 'sometimes|email|unique:users,email,' . $request->id,
        'password' => 'nullable|string|min:8',
        'role_ids' => 'required|array|min:1',
        'department' => 'nullable|string',
        'status' => 'nullable|integer|in:0,1',
    ]);

    if ($validator->fails()) {
        return $this->set_response(null, 422, 'error', $validator->errors()->all());
    }

    DB::beginTransaction();
    try {
        // 🔹 Only update real users table columns
        $data = [];

        if ($request->has('name')) {
            $data['name'] = $request->name;
        }

        if ($request->has('email')) {
            $data['email'] = $request->email;
        }

        if ($request->has('department')) {
            $data['department'] = $request->department;
        }

        if ($request->has('status')) {
            $data['status'] = $request->status;
        }

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        if (!empty($data)) {
            $data['updated_at'] = now();
            DB::table('users')->where('id', $request->id)->update($data);
        }

        // 🔹 Update roles (same logic as create)
        DB::table('model_has_roles')->where('model_id', $request->id)->delete();

        foreach ($request->role_ids as $roleId) {
            DB::table('model_has_roles')->insert([
                'role_id' => $roleId,
                'model_type' => 'App\Models\User',
                'model_id' => $request->id,
            ]);
        }

        DB::commit();
        return $this->set_response(null, 200, 'success', ['User updated successfully']);

    } catch (\Exception $e) {
        DB::rollBack();
        return $this->set_response(null, 422, 'error', ['Something went wrong: ' . $e->getMessage()]);
    }
}


    // Delete user

    public function deleteUser( $id ) {
        if ( !DB::table( 'users' )->where( 'id', $id )->exists() ) {
            return $this->set_response( null, 404, 'error', [ 'User not found' ] );
        }

        DB::table( 'users' )->where( 'id', $id )->delete();
        DB::table( 'model_has_roles' )->where( 'model_id', $id )->delete();

        return $this->set_response( null, 200, 'success', [ 'User deleted successfully' ] );
    }

 public function getUser(Request $request)
{
    $validator = Validator::make($request->all(), [
        'id' => 'required|numeric|exists:users,id',
    ]);

    if ($validator->fails()) {
        return $this->set_response(null, 422, 'error', $validator->errors()->all());
    }
    
    // Get user with specific fields including department
    $user = User::select('id', 'name', 'email', 'department', 'status', 'created_at', 'updated_at')
                ->find($request->id);
    
    if (!$user) {
        return $this->set_response(null, 404, 'error', ['User not found']);
    }
    
    $user_roles_permissions = $this->user_roles_permissions_q();
    
    // Convert to array and add roles and permissions
    $userData = $user->toArray();
    $userData['roles'] = $user_roles_permissions->where('user_id', $user->id)->pluck('role_name')->unique()->toArray();
    $userData['permissions'] = $user_roles_permissions->where('user_id', $user->id)->pluck('permission_name')->unique()->toArray();

    return $this->set_response($userData, 200, 'success', ['User data']);
}

    public function getAllUsers_p(Request $request)
    {
        // search
        $search = $request->search;
        if ($search!=null) {
            $data = User::orderBy('created_at', 'desc')->get();

            // search
            $data = $data->filter(function ($item) use ($search) {
                return false !== (stristr($item->name, $search) || stristr($item->full_name, $search) || stristr($item->email, $search) || stristr($item->contact, $search) );
            });

            $data = paginate($data);
        }
        else{
            $data = User::orderBy('created_at', 'desc')->paginate(10);
        }

        $data = [
            'paginator' => getFormattedPaginatedArray($data),
            'data' => $data->items(),
        ];
        return $this->set_response($data,  200,'success', ['Users data']);
    }
}

