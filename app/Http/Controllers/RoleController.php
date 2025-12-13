<?php

namespace App\Http\Controllers;

use App\Traits\Queries;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\Role as Role_c;
use App\Models\PermissionModules;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    use ApiResponser;
    use Queries;

    public function getAllRoles_p()
    {
        $data = Role_c::paginate(10);
        $data = [
            'paginator' => getFormattedPaginatedArray($data),
            'data' => $data->items(),
        ];
        return $this->set_response($data,  200,'success', ['Role list']);
    }

    public function getAllRoles(Request $request)
    {
        $req = $request->all();
        $roles = Role::all();
        return $this->set_response(['rolelist' => $roles],  200,'success', ['Role list']);
    }

    public function getRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }

        $role = Role::find($request->id);
        $role['permissions'] = (Role_c::where('id', $request->id)->with('permissions:id,name')->first())->permissions->pluck('name') ?? [];
        $role['modules'] = PermissionModules::select('id','name')
                            ->with('permissions:id,name,module_id')
                            ->whereHas('permissions', function ($q) use($role) {
                                return $q->whereIn('name', $role['permissions']);
                            })
                            ->get();

        return $this->set_response($role, 200,'success',  ['Role data']);
    }

    public function createRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles',
            'permissions' => 'required',
            'guard_name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        $req = $request->all();


        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name,
            ]);
            $permissions = DB::table('permissions')->get();
            foreach ($request->permissions as $key => $value)
            {
                DB::table('role_has_permissions')->insert(
                    [
                        'permission_id' => $permissions->where('name', $value)->pluck('id')->first(),
                        'role_id' => $role->id
                    ]
                );
            }
            DB::commit();
            return $this->set_response($role,  200,'success', ['Role Created']);
        } catch (\Exception $e) {
            DB::rollback();
            $logMessage = formatCommonErrorLogMessage($e);
            writeToLog($logMessage, 'error');
            return $this->set_response(null,  422,'error', ['Something went wrong. Please try again later!']);
        }
    }

    public function updateRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:roles,id',
            'name' => 'required|string|unique:roles,name,'.$request->id,
            'guard_name' => 'required|string',
            'permissions' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }


        $role = Role::find($request->id);
        DB::beginTransaction();
        try {
            $role->update([
                'name' => $request->name,
                'guard_name' => $request->guard_name,
            ]);
            // $role->syncPermissions($request->permissions);
            DB::table('role_has_permissions')->where('role_id', $request->id)->delete();
            $permissions = DB::table('permissions')->get();
            foreach ($request->permissions as $key => $value)
            {
                DB::table('role_has_permissions')->insert(
                    [
                        'permission_id' => $permissions->where('name', $value)->pluck('id')->first(),
                        'role_id' => $request->id
                    ]
                );
            }
            DB::commit();
            return $this->set_response($role, 200,'success', ['Role Updated']);
        } catch (\Exception $e) {
            DB::rollback();
            $logMessage = formatCommonErrorLogMessage($e);
            writeToLog($logMessage, 'error');
            return $this->set_response(null,  422,'error', ['Something went wrong. Please try again later!']);
        }
    }


    public function deleteRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:roles,id',
        ]);
        if ($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        Role::find($request->id)->update(['deleted_at' => getNow()]);
        return $this->set_response(null,  200,'success', ['Role deleted successfully']);
    }



}
