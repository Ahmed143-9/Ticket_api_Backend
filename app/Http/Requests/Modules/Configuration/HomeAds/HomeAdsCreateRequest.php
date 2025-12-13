<?php

namespace App\Http\Requests\Modules\Configuration\HomeAds;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class HomeAdsCreateRequest extends FormRequest
{
    use ApiResponser;

    public function rules()
    {
        $rules = [
            'file_name' => 'required|string',
            'file_path' => 'required|string',
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        return $messages;
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->set_response(null, 422, 'error', array_slice($validator->errors()->all(), 0, 2), formatErrors($validator)));
    }
}
