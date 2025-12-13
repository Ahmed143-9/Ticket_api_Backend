<?php


namespace App\Enum;


class StatusCodeEnum extends BaseEnum
{
    static $SUCCESS = 200;
    static $VALIDATION_ERROR = 422;
    static $SERVER_ERROR = 500;
    static $UNAUTHORIZE = 401;
    static $NOT_FOUND = 404;
    static $BAD_REQUEST = 400;
    static $FORBIDDEN = 403;
    static $GATEWAY_ERROR = 502;
}
