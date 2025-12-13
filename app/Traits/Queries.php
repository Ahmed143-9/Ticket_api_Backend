<?php

namespace App\Traits;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

trait Queries{

    protected function user_roles_permissions_q()
	{
		return DB::table('users')
                    ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                    ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->leftJoin('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
                    ->leftJoin('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                    ->select('users.id as user_id', 'roles.id as role_id', 'roles.name as role_name', 'permissions.id as permission_id', 'permissions.name as permission_name')
                    ->whereNull('roles.deleted_at')
                    ->whereNull('model_has_roles.deleted_at')
                    ->whereNull('role_has_permissions.deleted_at')
                    ->whereNull('permissions.deleted_at')
                    ->distinct()
                    ->get();
	}

    protected function role_permissions_q()
	{
		return DB::table('roles')
                    ->leftJoin('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
                    ->leftJoin('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                    ->select('roles.id as role_id', 'roles.name as role_name', 'permissions.id as permission_id', 'permissions.name as permission_name')
                    ->whereNull('roles.deleted_at')
                    ->whereNull('role_has_permissions.deleted_at')
                    ->whereNull('permissions.deleted_at')
                    ->distinct()
                    ->get();
	}



}
