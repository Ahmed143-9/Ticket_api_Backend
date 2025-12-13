<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Facades\Modules\SMS\SMSFacade;

class SmsService {
    private $numbers;
    private $message;
    private $masking;

    private $token;

    public function __construct( $number, $message,  $masking ) {
        $this->number = $number;
        $this->message = $message;
        $this->masking = $masking;

        $this->token = env('SMS_TOKEN', 'bHlyaWNpc3Q6bHlyaWNpc3RwYXNz');
    }

    public function singleSms() {
        $curl = curl_init();

        curl_setopt_array( $curl, array(
            CURLOPT_URL => 'https://api.wintextbd.com/SingleSms',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array( 'token' => $this->token, 'mobileno' => $this->number, 'SMSText' => $this->message, 'ismasking' => $this->masking, 'masking' => $this->masking, 'messagetype' => '1' ),
        ) );

        $response = curl_exec( $curl );

        curl_close( $curl );
    }

}
