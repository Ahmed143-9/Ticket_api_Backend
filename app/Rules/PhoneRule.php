<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneRule implements Rule
{
    protected $message;

    public function __construct($params=[])
    {
        $this->message = $params['message'] ?? null;
    }

    public function passes($attribute, $value)
    {
        return preg_match('%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i', $value) && strlen($value) >= 11 && strlen($value) <= 14;
    }

    public function message()
    {
        return $this->message ?? 'The :attribute must be a valid phone number.';
    }
}
