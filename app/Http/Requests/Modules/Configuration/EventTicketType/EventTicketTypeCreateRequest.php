<?php

namespace App\Http\Requests\Modules\Configuration\EventTicketType;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class EventTicketTypeCreateRequest extends FormRequest
{
    use ApiResponser;

    public function rules()
    {
        $rules = [
            'ticket_type' => [
                'required', 'string',
                Rule::unique('event_ticket_type')->where(fn ($query) =>
                    $query->where('event_id', $this->event_id)
                ),
            ],
            'event_id' => 'required|integer',
            'price' => 'required|numeric',
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'ticket_type.required' => 'Ticket type is required.',
            'ticket_type.string' => 'Ticket type must be a valid string.',
            'ticket_type.unique' => 'This ticket type is already assigned to the selected event.',

            'event_id.required' => 'Event ID is required.',
            'event_id.integer' => 'Event ID must be an integer.',

            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a numeric value.',
        ];

        return $messages;
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->set_response(null, 422, 'error', array_slice($validator->errors()->all(), 0, 2), formatErrors($validator)));
    }
}
