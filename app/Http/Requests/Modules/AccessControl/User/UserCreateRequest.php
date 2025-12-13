<?php

namespace App\Http\Requests\Modules\AccessControl\User;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class UserCreateRequest extends FormRequest
{
    use ApiResponser;

    public function rules()
    {
        $rules = [
            'email' => 'required|email|unique:users|max:255',
            'role_ids' => 'required|array|min:1',
            'password' => [
                    'required',
                    'string',
                    'min:8',             // must be at least 8 characters in length
                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    'regex:/[0-9]/',      // must contain at least one digit
                    'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'joining_date' => 'nullable|date',
        ];

        $role_ids = $this->input('role_ids');
        if (isset($role_ids))
        {
            foreach ($role_ids as $rowNumber => $item)
            {
                $rules["role_ids.{$rowNumber}"] = "required|numeric|exists:roles,id";
            }
        }

        return $rules;
    }
    public function messages()
    {
        $messages = [
            'password.regex' => "Password must contain at least one upper case, lower case letter and number and special character.",
            'role_ids.required' => "At lease 1 role is required."
        ];

        $role_ids = $this->input('role_ids');
        if (isset($role_ids))
        {
            foreach ($role_ids as $rowNumber => $item) {
                $messages["role_ids.{$rowNumber}.required"] = "Role-".($rowNumber+1).": Role is required.";
                $messages["role_ids.{$rowNumber}.exists"] = "Role-".($rowNumber+1).": Invalid Role.";
            }
        }

        return $messages;
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->set_response(null, 422, 'error', array_slice($validator->errors()->all(), 0, 2),
                new Request (array_merge( $this->request->all() ))
            )
        );
    }
}
