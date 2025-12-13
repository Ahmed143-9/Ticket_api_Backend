<?php

namespace App\Http\Requests\Modules\Configuration\HomeMainSlider;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class HomeMainSliderCreateRequest extends FormRequest
{
    use ApiResponser;

public function rules()
{
    return [
        'file_name' => 'required|string',
        'file_path' => 'required|string',
        'text' => 'nullable|string|max:255',
        'description' => 'nullable|string',
    ];
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
