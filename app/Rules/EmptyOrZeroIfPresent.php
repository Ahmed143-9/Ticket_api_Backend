<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmptyOrZeroIfPresent implements Rule
{
    private $otherField;

    public function __construct($otherField)
    {
        $this->otherField = $otherField;
    }

    public function passes($attribute, $value)
    {
        $otherFieldValue = request()->input($this->otherField);

        return empty($otherFieldValue) || $value === 0 || empty($value);
    }

    public function message()
    {
        return 'The :attribute must be empty or zero if ' . $this->otherField . ' is present.';
    }
}
