<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\Queries;
use App\Traits\ApiResponser;
use App\Models\OauthAccessToken;
use Illuminate\Support\Facades\Auth;

class AuthLogViewerMiddleware
{
    use ApiResponser;
    use Queries;

    public function handle($request, Closure $next)
    {
        if (isset($request->password))
        {

            if ($request->password==\config('app.log_password')) {
                return $next($request);
            }
            return $this->set_response(null,401,'error',['Unauthenticated.']);
        }
        else{
            return $this->set_response(null,401,'error',['Unauthenticated.']);
        }
    }

}
