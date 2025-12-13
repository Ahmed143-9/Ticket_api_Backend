<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\Queries;
use App\Rules\PhoneRule;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use App\Models\OauthAccessToken;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponser, Queries;

    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email|exists:users,email|max:255',
    //         'password' => 'required|string|min:8',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->set_response(null, 422, 'failed', $validator->errors()->all());
    //     }

    //     if (!(User::where('email', $request->email)->pluck('status')->first()==1)) {
    //         return $this->set_response(null, 422, 'failed', ['User is inactive! Plaese contact administrator.']);
    //     }

    //     if (!Auth::attempt($request->except(['remember_me']))) {
    //         return $this->set_response(null, 422, 'failed', ['Credentials mismatch']);
    //     }

    //     $personalAccessToken = $this->getPersonalAccessToken();


    //     $user = Auth::user();
    //     $user_roles_permissions = $this->user_roles_permissions_q();
    //     $roles = $user_roles_permissions->where('user_id', $user->id)->pluck('role_name')->unique()->toArray();
    //     $permissions = $user_roles_permissions->where('user_id', $user->id)->pluck('permission_name')->unique()->toArray();

    //     $tokenData = [
    //         'user' => [
    //             'access_token' => $personalAccessToken->accessToken,
    //             'token_type' => 'Bearer',
    //             'expires_at' => Carbon::parse($personalAccessToken->token->expires_at)->toDateTimeString(),
    //             'name' => $user->name,
    //             'email' => $user->email,
    //             'userId' => $user->id,
    //             'force_password' => $user->force_password,
    //         ],
    //         'roles' => $roles,
    //         'permissions' => $permissions,
    //     ];

    //     // Laravel passport prevent user to login together with the same credential
    //     OauthAccessToken::where('user_id', $user->id)->orderBy('created_at', 'desc')->skip(200)->limit(100)->get()->map(function ($q) {
    //         return $q->update([
    //             'revoked' => 1
    //         ]);
    //     });

    //     return $this->set_response($tokenData, 200, 'success', ['Logged in!']);
    // }
    public function login(Request $request)
        // Add this debug logging
  
    {
          \Log::info('Login attempt', [
        'url' => $request->fullUrl(),
        'method' => $request->method(),
        'ip' => $request->ip(),
        'headers' => $request->headers->all(),
        'body' => $request->all()
    ]);
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email|max:255',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        if (!Auth::attempt($request->except(['remember_me']))) {
            return $this->set_response(null, 422, 'failed', ['Credentials mismatch']);
        }

        $personalAccessToken = $this->getPersonalAccessToken();

        $user = Auth::user();
        $user_roles_permissions = $this->user_roles_permissions_q();
        $roles = $user_roles_permissions->where('user_id', $user->id)->pluck('role_name')->unique()->toArray();
        $permissions = $user_roles_permissions->where('user_id', $user->id)->pluck('permission_name')->unique()->toArray();

        $tokenData = [
            'user' => [
                'access_token' => $personalAccessToken->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($personalAccessToken->token->expires_at)->toDateTimeString(),
                'name' => $user->name,
                'email' => $user->email,
                'userId' => $user->id,
                'force_password' => $user->force_password,
            ],
            'roles' => $roles,
            'permissions' => $permissions,
        ];

        // Laravel passport prevent user to login together with the same credential
        OauthAccessToken::where('user_id', $user->id)->orderBy('created_at', 'desc')->skip(200)->limit(100)->get()->map(function ($q) {
            return $q->update([
                'revoked' => 1
            ]);
        });

        return $this->set_response($tokenData, 200, 'success', ['Logged in!']);
    }




    public function signup(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        Auth::attempt(['email' => $request->email, 'password' => $request->password]);

        $personalAccessToken = $this->getPersonalAccessToken();

        $user = Auth::user();
        $user_roles_permissions = $this->user_roles_permissions_q();
        $roles = $user_roles_permissions->where('user_id', $user->id)->pluck('role_name')->unique()->toArray();
        $permissions = $user_roles_permissions->where('user_id', $user->id)->pluck('permission_name')->unique()->toArray();


        $tokenData = [
            'access_token' => $personalAccessToken->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($personalAccessToken->token->expires_at)->toDateTimeString(),
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $roles,
            'permissions' => $permissions,
        ];

        return $this->set_response($tokenData, 200, 'success', ['User Created!']);
    }

    public function me(Request $request)
    {
        $user = Auth::user();
        $user_roles_permissions = $this->user_roles_permissions_q();
        $roles = $user_roles_permissions->where('user_id', $user->id)->pluck('role_name')->unique()->toArray();
        $permissions = $user_roles_permissions->where('user_id', $user->id)->pluck('permission_name')->unique()->toArray();

        $token_management = token_management($request, $user);

        $tokenData = [
            'user' => [
                'access_token' => $token_management['token'],
                'token_type' => 'Bearer',
                'expires_at' => $token_management['expires'],
                'name' => $user->name,
                'email' => $user->email,
                'userId' => $user->id,
                'force_password' => $user->force_password,
            ],
            'roles' => $roles,
            'permissions' => $permissions,
        ];


        return $this->set_response($tokenData, 200, 'success', ['My data.']);
    }

    public function logout()
    {
        Auth::user()->token()->revoke();

        OauthAccessToken::where('user_id', Auth::user()->id)
                            ->update([
                                'revoked' => 1
                            ]);

        return $this->set_response(null, 200, 'success', ['User Logged Out!']);
    }

    public function getPersonalAccessToken()
    {
        if (request()->remember_me == true) {
            Passport::personalAccessTokensExpireIn(now()->addDays(15));
        }
        return Auth::user()->createToken('Personal Access Token');
    }



    public function validateSignup($request)
    {
        return $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
    }




    public function profileUpdate(Request $request)
    {
         // prerequisite validation
        $validator = Validator::make($request->all(), [
                // 'id' => 'required|numeric|exists:users,id',
                'phone' => [new PhoneRule],
                'current_password' => [
                                        'nullable',
                                        'string',
                                        'min:8',             // must be at least 8 characters in length
                ],
                'new_password' => [
                                    'required_with:current_password',
                                    'different:current_password',
                                    'string',
                                    'min:8',             // must be at least 8 characters in length
                                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                                    'regex:/[0-9]/',      // must contain at least one digit
                                    'regex:/[@$!%*#?&]/', // must contain a special character
                ],
                'new_confirm_password' => 'required_with:new_password|same:new_password|string|min:8',
            ],
            [
                'new_password.regex' => "New password must contain at least one upper case, lower case letter and one number and one special character."
            ]
        );
        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }


        DB::beginTransaction();
        try {
            User::find(auth()->user()->id)->update($request->only([ 'name', 'phone' ]));

            if ($request->current_password!=null) {
                $this->profileChangePassword($request);
            }

            DB::commit();
            return $this->set_response(null,  200, 'success', ['Profile successfully updated!']);
        } catch (\Exception $e) {
            DB::rollback();
            $logMessage = formatCommonErrorLogMessage($e);
            writeToLog($logMessage, 'error');
            return $this->set_response(null,  400,'error', [$e->getMessage()]);
        }
    }

    public function profileChangePassword($request)
    {
        $user = Auth::user();

        $existing_password = $user->password; // db password already in bcrypt

        $current_password = $request->current_password;
        $new_password = $request->new_password;
        $new_confirm_password = $request->new_confirm_password;

        if (Hash::needsRehash($existing_password)) {
            $existing_password = bcrypt($existing_password);
        }

        if (Hash::check($current_password, $existing_password)) {
            User::find($user->id)->update(
                    [
                        'password' => bcrypt($request->new_password),
                        'force_password' => 0
                    ]
                );
        } else {
            throw new Exception("Invalid current password given!");
        }

    }


    public function changePassword(Request $request)
    {
        // prerequisite validation
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
            'currentPassword' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }

        // previous password check
        $existingPassword = DB::table('users')->where('id', auth()->user()->id)->pluck('password')->first(); // db password already in bcrypt
        $currentPassword = $request->currentPassword;

        // dd($existingPassword);
        // dd($currentPassword);

        if (Hash::needsRehash($existingPassword)) {
            $existingPassword = bcrypt($existingPassword);
        }

        // dd($existingPassword);

        // dd(Hash::check($currentPassword, $existingPassword));

        if (Hash::check($currentPassword, $existingPassword)) {
            User::find(auth()->user()->id)->update(
                [
                    'password' => bcrypt($request->password)
                ]
            );
        } else {
            return $this->set_response(null, 422, 'error', ['Invalid current password given!']);
        }

        return $this->set_response(null,  200, 'success', ['Password successfully changed!']);
    }


}
