<?php

namespace App\Http\Controllers;

use App\Traits\Queries;
use App\Enum\PaginationEnum;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Models\Permission as Permission_c;

class PermissionController extends Controller
{
    use ApiResponser;
    use Queries;

    public function getAllpermissions(Request $request)
    {
        $permissions = Permission::orderBy('name','asc')->get();
        return $this->set_response(['permissionlist' => $permissions], 200,'success', ['Permission list']);
    }

    public function getAllPermissions_p(Request $request)
    {
        $permissions = Permission_c::with('module:id,name')
                        ->when(request()->filled('search'), function ($query) {
                            $query->whereRaw("LOWER(name) LIKE '%" . request("search")."%'");
                        });


        $data = $permissions->paginate(PaginationEnum::$DEFAULT);
        $data = [
            'paginator' => getFormattedPaginatedArray($data),
            'data' => $data->items(),
        ];
        return $this->set_response($data, 200,'success', ['Permission list']);
    }



    public function createPermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions',
            'guard_name' => 'required|string',
            'module_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        $req = $request->all();
        DB::beginTransaction();
        try {
            $permission = Permission::updateOrCreate([
                'name' => $request->name,
                'guard_name' => $request->guard_name,
                'module_id' => $request->module_id
            ]);
            DB::commit();
            return $this->set_response($permission,  200,'success', ['Permission Created']);
        } catch (\Exception $e) {
            DB::rollback();
            $logMessage = formatCommonErrorLogMessage($e);
            writeToLog($logMessage, 'error');
            return $this->set_response(null,  422,'error', ['Something went wrong. Please try again later!']);
        }
    }

    public function getPermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }

        $permission = Permission::find($request->id);
        return $this->set_response($permission, 200,'success',  ['Permission data']);
    }

    public function updatePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required|string|unique:permissions,name,'.$request->id,
            'guard_name' => 'required|string',
            'module_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        $permission = Permission::find($request->id);

        DB::beginTransaction();
        try {
            $permission->update([
                'name' => $request->name,
                'guard_name' => $request->guard_name,
                'module_id' => $request->module_id
            ]);
            DB::commit();
            return $this->set_response($permission, 200,'success', ['Permission Updated']);
        } catch (\Exception $e) {
            DB::rollback();
            $logMessage = formatCommonErrorLogMessage($e);
            writeToLog($logMessage, 'error');
            return $this->set_response(null,  422,'error', ['Something went wrong. Please try again later!']);
        }
    }

    public function deletePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:permissions,id',
        ]);
        if ($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        Permission::find($request->id)->update(['deleted_at' => getNow()]);
        return $this->set_response(null,  200,'success', ['Permission deleted successfully']);
    }

}
