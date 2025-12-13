<?php

namespace App\Rules;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

class Specific1TblColCondCheck implements Rule
{
    protected $table;
    protected $conditions = [];
    protected $message;
    protected $output_type;

    public function __construct($params=[])
    {
        $this->table = $params['table'] ?? '';
        $this->conditions = $params['conditions'] ?? [];
        $this->message = $params['message'] ?? null;
        $this->output_type = $params['output_type'] ?? 'positive';  // positive, negative
    }

    public function passes($attribute, $value)
    {
        $query = DB::table($this->table);
        foreach ($this->conditions as $key => $condition)
        {
            $query = isset($condition['conditional_column']) ?
                $query->where($condition['conditional_column'], $condition['conditional_column_condition'] ?? '=', $condition['conditional_column_value'])
                : $query;
        }

        $count = $query->count();
        $condition = $count>0 ? 1 : 0;
        $condition = $this->output_type == 'positive' ? $condition : !$condition;
        return $condition;
    }

    public function message()
    {
        return $this->message ?? 'Condition not satisfied';
    }
}

