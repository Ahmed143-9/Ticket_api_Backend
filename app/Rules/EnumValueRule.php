<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EnumValueRule implements Rule
{
    protected $enumClass;

    public function __construct(string $enumClass)
    {
        $this->enumClass = $enumClass;
    }

    public function passes($attribute, $value)
    {
        return in_array($value, $this->enumClass::getValues());
    }

    public function message()
    {
        return 'The :attribute is not a valid enum value.';
    }
}
