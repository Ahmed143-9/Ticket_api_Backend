<?php

namespace App\Rules;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

class Specific1ColumnDataMatchingAgainstAnother1Column implements Rule
{
    protected $table;
    protected $conditional_column;
    protected $value_check_column;
    protected $message;

    public function __construct($params=[])
    {
        $this->table = $params['table'] ?? '';
        $this->conditional_column = $params['conditional_column'] ?? '';
        $this->value_check_column = $params['value_check_column'] ?? '';
        $this->message = $params['message'] ?? null;
    }


    public function passes($attribute, $value)
    {
        $condition = 1;
        $value_check_column_data_first = '';
        foreach ($value as $key => $item)
        {
            $value_check_column_current_data = DB::table($this->table)   // get unit_id against id (get data)
                                        ->where($this->conditional_column, $item)
                                        ->pluck($this->value_check_column)
                                        ->first();

            if($key==0)
            {
                $condition = 1;
                $value_check_column_data_first = $value_check_column_current_data;  // store first data
            }
            else
            {
                if ($value_check_column_data_first!=$value_check_column_current_data) // check first data is equal to curent data
                {
                    $condition = 0;
                }
            }
        }
        return $condition;
    }

    public function message()
    {
        return $this->message ?? 'For all '.$this->conditional_column.' must have same '.Str::title(str_replace('_', ' ', $this->value_check_column));
    }
}


// if i want to check if  export_pi table's column id (781, 9, 149, 220) have same unit_id
// then we can use this
