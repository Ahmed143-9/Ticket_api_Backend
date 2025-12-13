<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Traits\Queries;

class CheckPermission
{
    use ApiResponser;
    use Queries;

    public function handle($request, Closure $next, $permission)
    {
        if (Auth::check())
        {
            $user = Auth::user();
            $user_roles_permissions = $this->user_roles_permissions_q();
            $permissions = $user_roles_permissions->where('user_id', $user->id)->pluck('permission_name')->unique()->toArray();

            if (!(isset($permissions) && in_array($permission, $permissions)))
            {
                return $this->set_response(null, 403, 'error', ['You do not have required permission('.$permission.')!']);
            }

            $token_management = token_management($request, $user);

            return $next($request);
        }
        else{
            return $this->set_response(null,401,'error',['Unauthenticated.']);
        }
    }

}
