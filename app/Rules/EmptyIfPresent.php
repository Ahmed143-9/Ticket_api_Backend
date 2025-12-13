<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmptyIfPresent implements Rule
{
    private $otherField;

    public function __construct($otherField)
    {
        $this->otherField = $otherField;
    }

    public function passes($attribute, $value)
    {
        return empty(request()->input($this->otherField)) || empty($value);
    }

    public function message()
    {
        return 'The :attribute must be empty if ' . $this->otherField . ' is present.';
    }
}
