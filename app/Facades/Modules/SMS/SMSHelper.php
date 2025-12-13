<?php

namespace App\Facades\Modules\SMS;

use App\Services\SmsService;

class SMSHelper
{

    public function getSMSNumbers($is_unicode, $message)
    {
        $messageLength = mb_strlen($message) ?? 0;

        if ($is_unicode)
        {
            if ($messageLength <= 70) {
                $no_of_msg = 1;
            } elseif ($messageLength > 70) {
                if ($messageLength >= 67 && $messageLength <= 134) {
                    $no_of_msg = 2;
                } elseif ($messageLength >= 135 && $messageLength <= 201) {
                    $no_of_msg = 3;
                } elseif ($messageLength >= 202 && $messageLength <= 268) {
                    $no_of_msg = 4;
                } elseif ($messageLength >= 269 && $messageLength <= 335) {
                    $no_of_msg = 5;
                } elseif ($messageLength >= 336 && $messageLength <= 402) {
                    $no_of_msg = 6;
                } elseif ($messageLength >= 403 && $messageLength <= 469) {
                    $no_of_msg = 7;
                } elseif ($messageLength >= 470 && $messageLength <= 536) {
                    $no_of_msg = 8;
                } elseif ($messageLength >= 537 && $messageLength <= 603) {
                    $no_of_msg = 9;
                }
            }
        }
        else
        {
            if ($messageLength <= 160) {
                $no_of_msg = 1;
            } elseif ($messageLength > 160) {
                if ($messageLength > 153 && $messageLength <= 306) {
                    $no_of_msg = 2;
                } elseif ($messageLength > 306 && $messageLength <= 459) {
                    $no_of_msg = 3;
                } elseif ($messageLength > 459 && $messageLength <= 612) {
                    $no_of_msg = 4;
                } elseif ($messageLength > 612 && $messageLength <= 765) {
                    $no_of_msg = 5;
                } elseif ($messageLength > 765 && $messageLength <= 918) {
                    $no_of_msg = 6;
                }
            }
        }
        return $no_of_msg;
    }
}
