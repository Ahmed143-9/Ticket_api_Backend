<?php

namespace App\Http\Requests\Modules\Configuration\Members;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class MembersUpdateRequest extends FormRequest
{
    use ApiResponser;

    public function rules()
    {
        $rules = [
            'id' => 'required|integer|exists:members,id',

            'name' => 'required|string',
            'position' => 'nullable|string',
            'bio' => 'required|string',
            'youtube_url' => 'nullable|string|unique:members,youtube_url,'.$this->id,
            'file_name' => 'nullable|string',
            'file_path' => 'nullable|string',
            'is_featured' => 'nullable|in:0,1',
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
