<?php

namespace App\Rules;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

class Specific1ColumnUnique implements Rule {
    protected $tables;
    protected $message;

    public function __construct( $params = [] ) {
        $this->tables = $params[ 'tables' ] ?? [];
        $this->message = $params[ 'message' ] ?? null;
    }

    public function passes( $attribute, $value ) {
        $condition = 1; // default not exist | pass

        $tables = $this->tables;

        foreach ( $tables as $key => $table ) {

            $table_name = $table['table_name'] ?? null;
            $column_name = $table['column_name'] ?? null;
            $soft_delete_check = $table['soft_delete_check'] ?? null;

            $exist = DB::table($table_name)
                    ->when(isset($soft_delete_check), function ($q){
                        $q->whereNull('deleted_at');
                    })
                    ->where($column_name, $value)
                    ->exists();

            if($exist)  $condition = 0;  // exist, means not unique (not pass)  and !exist, means unique (pass)
        }
        return $condition;
    }

    public function message() {
        return $this->message ?? Str::title(str_replace('_', ' ', $this->tables[0]['column_name'] ?? '')).' must be unique.';
    }
}

