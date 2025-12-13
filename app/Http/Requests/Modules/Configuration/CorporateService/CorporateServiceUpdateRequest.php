<?php

namespace App\Http\Requests\Modules\Configuration\CorporateService;

use Illuminate\Foundation\Http\FormRequest;

class CorporateServiceUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'id' => 'required|exists:corporateservices,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ];
    }
}