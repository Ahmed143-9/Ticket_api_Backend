<?php

namespace App\Http\Requests\Modules\Configuration\Product;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class ProductUpdateRequest extends FormRequest
{
    use ApiResponser;

    public function rules()
    {
        $rules = [
            'id' => 'required|integer|exists:product,id',

            'name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'member_id' => 'required|numeric|exists:members,id',
            'file_name' => 'nullable|string',
            'file_path' => 'nullable|string',
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
