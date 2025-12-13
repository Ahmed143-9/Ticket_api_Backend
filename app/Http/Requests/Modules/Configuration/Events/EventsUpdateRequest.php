<?php

namespace App\Http\Requests\Modules\Configuration\Events;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class EventsUpdateRequest extends FormRequest
{
    use ApiResponser;

    public function rules()
    {
        $rules = [
            'id' => 'required|integer|exists:events,id',

            'title' => 'required|string',
            'artist' => 'required|string',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string',
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
