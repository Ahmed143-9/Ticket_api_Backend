<?php

namespace App\Traits;

use Illuminate\Support\Facades\Response;

trait ApiResponser{

    public static function  set_response($data, $status_code, $status, $details, $errors=null)
    {
        $resData = Response::json(
                [
                    'status'        =>  $status, // 1 / 0
                    'code'          =>  $status_code,
                    'data'          =>  $data,
                    'message'       =>  $details,
                    'errors'       =>  $errors,
                ]
        , 200, [])
        ->header('Content-Type', 'application/json');

        return $resData;
    }

    public static function status_code_handler($status_code)
    {
        if ($status_code==200) return 'info';
        else if ($status_code==401) return 'warning';
        else if ($status_code==500) return 'debug';
        else return 'error';
    }
}
