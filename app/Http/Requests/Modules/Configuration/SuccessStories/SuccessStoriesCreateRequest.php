<?php

namespace App\Http\Requests\Modules\Configuration\SuccessStories;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class SuccessStoriesCreateRequest extends FormRequest
{
    use ApiResponser;

    public function rules()
    {
        $rules = [
            'headline' => 'required|string',
            // 'subheading' => 'required|string',
            'details' => 'nullable|string',
            // 'member_id' => 'required|string',
            'posting_time' => 'nullable|string',

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
