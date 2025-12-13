<?php

namespace App\Rules;

use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\Rule;

class RoundedLessThanOrEqual implements Rule
{
    protected $otherColumn;
    protected $precision;
    protected $roundedOtherColumn;

    public function __construct($otherColumn, $precision=0)
    {
        $this->otherColumn = $otherColumn;
        $this->precision = $precision;
    }

    public function passes($attribute, $value)
    {
        $roundedOtherColumn = round(request()->input($this->otherColumn), $this->precision);
        $this->roundedOtherColumn = $roundedOtherColumn;
        return round($value, $this->precision) <= round($roundedOtherColumn, $this->precision);
    }

    public function message()
    {
        return 'The :attribute must be less than or equal to '.Str::title(str_replace('_', ' ', $this->otherColumn)).' ('.$this->roundedOtherColumn.')';
    }
}
