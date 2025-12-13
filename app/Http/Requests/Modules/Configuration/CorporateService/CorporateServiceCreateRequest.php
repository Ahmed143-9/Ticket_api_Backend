<?php

namespace App\Http\Requests\Modules\Configuration\CorporateService;

use Illuminate\Foundation\Http\FormRequest;

class CorporateServiceCreateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ];
    }
}