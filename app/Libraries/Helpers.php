<?php

use App\Models\OauthAccessToken;
use Illuminate\Support\Facades\DB;
use App\Models\PresetDBAccessDetails;
use App\Libraries\RSA;

    function oneTo99Check($number)
    {
        // $number = 1;
        // $number = 101;
        // $number = 99;
        // $number = 199;
        $condition = false;


        $number = $number%100;
        // dd($number);
        if (in_array($number, range(1, 99)))
        {
            $condition=true;
        }

        return $condition;
    }

    function numberFormat($number, $decimals=0)
    {

        // $number = 555;
        // $decimals=0;
        // $number = 555.000;
        // $number = 555.123456;

        if (strpos($number,'.')!=null)
        {
            $decimalNumbers = substr($number, strpos($number,'.'));
            $decimalNumbers = substr($decimalNumbers, 1, $decimals);
        }
        else
        {
            $decimalNumbers = 0;
            for ($i = 2; $i <=$decimals ; $i++)
            {
                $decimalNumbers = $decimalNumbers.'0';
            }
        }
        // return $decimalNumbers;



        $number = (int) $number;
        // reverse
        $number = strrev($number);

        $n = '';
        $stringlength = strlen($number);

        for ($i = 0; $i < $stringlength; $i++)
        {
            if ($i%2==0 && $i!=$stringlength-1 && $i>1)
            {
                $n = $n.$number[$i].',';
            }
            else
            {
                $n = $n.$number[$i];
            }
        }

        $number = $n;
        // reverse
        $number = strrev($number);

        ($decimals!=0)? $number=$number.'.'.$decimalNumbers : $number ;

        return $number;
    }

    function partially_hide_email($email)
    {
        $em   = explode("@",$email);
        $name = implode('@', array_slice($em, 0, count($em)-1));
        // $len  = floor(strlen($name)/2);
        $len  = 5;
        $afterat = end($em);
        $afteratbeforedot = substr($afterat,0,strpos($afterat, '.'));
        $afteratbeforedot2char = substr($afteratbeforedot, -2);
        $afteratafterdot = substr($afterat,strpos($afterat, '.'));

        return substr($name,0, 2) . str_repeat('*', $len) . "@" .str_repeat('*', $len). $afteratbeforedot2char.$afteratafterdot;
    }

    function getLastWord($sentence)
    {
        $pieces = explode(' ', $sentence);
        $last_word = array_pop($pieces);

        return $last_word;
    }




    function dmyToYmd($date)
    {
        if ($date) {
            $date = substr($date, 6,4).'-'.substr($date, 3,2).'-'.substr($date, 0,2);
            return $date;
        } else {
            return '';
        }
    }

    if (!function_exists('getTimeStamp')) {

        function getTimeStamp()
        {
            return \Carbon\Carbon::now('+06:00')->format('YmdHis');
        }
    }

    // carbondatetimeToYmd(\Carbon\Carbon::now('+06:00'))
    function carbondatetimeToYmd($datetime)
    {
        if ($datetime) {
            $date = \Carbon\Carbon::parse($datetime)->format('Y-m-d');
            return $date;
        } else {
            return '';
        }
    }

    // carbondatetimeToDayNumberOfYear('2021-02-12 17:04:05.084512')
    // carbondatetimeToDayNumberOfYear(\Carbon\Carbon::now('+06:00'))
    function carbondatetimeToDayNumberOfYear($datetime)
    {
        if ($datetime) {
            $DayNumberOfYear = \Carbon\Carbon::parse($datetime)->dayOfYear ;
            return $DayNumberOfYear+1;
        } else {
            return '';
        }
    }

    function YmdTodmY($date)
    {
        if ($date) {
            $date = \Carbon\Carbon::parse($date)->format('d-m-Y');
            return $date;
        } else {
            return '';
        }
    }

    function YmdTodmYDay($date)
    {
        if ($date) {
            $date = \Carbon\Carbon::parse($date)->format('d-m-Y  (l)');
            return $date;
        } else {
            return '';
        }
    }

    function YmdTodmYPm($datetime)
    {
        if ($datetime) {
            $date = \Carbon\Carbon::parse($datetime)->format('d-m-Y  g:i A');
            return $date;
        } else {
            return '';
        }
    }

    function YmdTodmYPmgiA($datetime)
    {
        if ($datetime) {
            $date = \Carbon\Carbon::parse($datetime)->format('g:i A');
            return $date;
        } else {
            return '';
        }
    }

    function YmdTodmYPmdMy($datetime)
    {
        if ($datetime) {
            $date = \Carbon\Carbon::parse($datetime)->format('d M, y ');
            return $date;
        } else {
            return '';
        }
    }

    function getCurrentYear(){
        return now()->year;
    }


    function mailformat1($mailReceiverEmail, $mailReceiverName, $mailSenderEmail, $mailSenderName , $subject, $bodyMessage, $website, $contactMails, $numberTitle, $number, $logo, $cartData, $cartdetailsData, $genericpacksizes_with_customer_price_Data, $countryData, $deliverymethodsData)
    {
        $data = array(
            [
                'mailReceiverEmail' => $mailReceiverEmail,
                'mailReceiverName' => $mailReceiverName,
                'mailSenderEmail' => $mailSenderEmail,
                'mailSenderName' => $mailSenderName ,
                'subject' => $subject,
                'bodyMessage' => $bodyMessage,
                'website' => $website,
                'contactMails' => $contactMails,
                'numberTitle' => $numberTitle,
                'number' => $number,
                'logo' => $logo,
                'cartData' => $cartData,
                'cartdetailsData' => $cartdetailsData,
                'genericpacksizes_with_customer_price_Data' => $genericpacksizes_with_customer_price_Data,
                'countryData' => $countryData,
                'deliverymethodsData' => $deliverymethodsData

            ]
        );

        // dd($data);
        // dd($data[0]);
        // dd($data[0]['mailSenderEmail']);



        try{
            Mail::send('mails.mailformat1', $data[0], function ($message)  use ($data) {
                $message->to($data[0]['mailReceiverEmail'], $data[0]['mailReceiverName'])
                        ->bcc($data[0]['mailSenderEmail'])
                        // ->bcc('saifuroracle@gmail.com')
                        ->bcc('medicineforworld@gmail.com')
                        // ->bcc('masudalimran92@gmail.com')
                        // ->bcc('yasir08arafat@yahoo.com')
                        ->bcc('medicineforworld@icloud.com')
                        ->bcc('medicineforworld@yahoo.com')
                        // ->bcc('saifur1193@hotmail.com')
                        // ->bcc('saifurrahman1993@yahoo.com')
                        ->replyTo('info@medicineforworld.com.bd', 'Medicine For World')
                        ->sender('noreply@medicineforworld.com.bd', 'Medicine For World')
                        ->priority(1)
                        ->returnPath('noreply@medicineforworld.com.bd')
                        ->subject($data[0]['subject']);
                $message->from($data[0]['mailSenderEmail'], $data[0]['mailSenderName']);
            });
        }
        catch (Exception $e) {
            DB::table('errors')->insert([
                'error' => 'Mail Sending Error - Order related -'.$e->getMessage()
            ]);
        }

    }






    function mailformat2($mailReceiverEmail, $mailReceiverName, $mailSenderEmail, $mailSenderName , $subject, $bodyMessage, $website, $contactMails, $numberTitle, $number, $logo)
    {
        $data = array(
            [
                'mailReceiverEmail' => $mailReceiverEmail,
                'mailReceiverName' => $mailReceiverName,
                'mailSenderEmail' => $mailSenderEmail,
                'mailSenderName' => $mailSenderName ,
                'subject' => $subject,
                'bodyMessage' => $bodyMessage,
                'website' => $website,
                'contactMails' => $contactMails,
                'numberTitle' => $numberTitle,
                'number' => $number,
                'logo' => $logo,
            ]
        );

        // dd($data);
        // dd((object)($data[0]));

        $data = (object)($data[0]);

        try{
            Mail::send('mails.mailformat2', (array) $data, function ($message)  use ($data) {
                $message->to($data->mailReceiverEmail, $data->mailReceiverName)
                        // ->bcc($data->mailSenderEmail)
                        // ->replyTo($data->mailSenderEmail,  $data->mailSenderName)
                        ->sender($data->mailSenderEmail,  $data->mailSenderName)
                        // ->priority(1)
                        // ->returnPath($data->['mailSenderEmail'])
                        // ->subject('This is last week rev report!');
                        ->subject($data->subject);
                        $message->from($data->mailSenderEmail,  $data->mailSenderName);
                });

        }
        catch (Exception $e) {
            // dd($e->getMessage());
            return App\Traits\ApiResponser::set_response(null, 400, 'error', $e->getMessage());
        }
    }

    function mailformat2_1($mailReceiverEmail, $mailReceiverName, $mailSenderEmail, $mailSenderName , $subject, $bodyMessage, $website, $contactMails, $numberTitle, $number, $logo)
    {
        $data = array(
            [
                'mailReceiverEmail' => $mailReceiverEmail,
                'mailReceiverName' => $mailReceiverName,
                'mailSenderEmail' => $mailSenderEmail,
                'mailSenderName' => $mailSenderName ,
                'subject' => $subject,
                'bodyMessage' => $bodyMessage,
                'website' => $website,
                'contactMails' => $contactMails,
                'numberTitle' => $numberTitle,
                'number' => $number,
                'logo' => $logo,
            ]
        );

        // dd($data);
        // dd($data[0]);
        // dd($data[0]['mailSenderEmail']);



        try{
            Mail::send('mails.mailformat2', $data[0], function ($message)  use ($data) {
                $message->to('medicineforworld@gmail.com', $data[0]['mailReceiverName'])
                        ->bcc($data[0]['mailSenderEmail'])
                        // ->bcc('saifuroracle@gmail.com')
                        // ->bcc('masudalimran92@gmail.com')
                        ->bcc('medicineforworld@icloud.com')
                        ->bcc('medicineforworld@yahoo.com')
                        // ->bcc('saifur1193@hotmail.com')
                        // ->bcc('saifurrahman1993@yahoo.com')
                        ->replyTo('info@medicineforworld.com.bd', 'Medicine For World')
                        ->sender('noreply@medicineforworld.com.bd', 'Medicine For World')
                        ->priority(1)
                        ->returnPath('noreply@medicineforworld.com.bd')
                        ->subject($data[0]['subject']);
                $message->from($data[0]['mailSenderEmail'], $data[0]['mailSenderName']);
            });
        }
        catch (Exception $e) {
            DB::table('errors')->insert([
                'error' => 'Mail Sending Error - Order related -'.$e->getMessage()
            ]);
        }

    }



    function mailformat2_2($mailReceiverEmail, $mailReceiverName, $mailSenderEmail, $mailSenderName , $subject, $bodyMessage, $website, $contactMails, $numberTitle, $number, $logo)
    {
        $data = array(
            [
                'mailReceiverEmail' => $mailReceiverEmail,
                'mailReceiverName' => $mailReceiverName,
                'mailSenderEmail' => $mailSenderEmail,
                'mailSenderName' => $mailSenderName ,
                'subject' => $subject,
                'bodyMessage' => $bodyMessage,
                'website' => $website,
                'contactMails' => $contactMails,
                'numberTitle' => $numberTitle,
                'number' => $number,
                'logo' => $logo,
            ]
        );


        Mail::send('mails.mailformat2', $data[0], function ($message)  use ($data) {
            $message->to($data[0]['mailReceiverEmail'], $data[0]['mailReceiverName'])
                    ->bcc('medicineforworld@gmail.com')
                    ->bcc('medicineforworld@icloud.com')
                    ->bcc('medicineforworld@yahoo.com')
                    // ->bcc('masudalimran92@gmail.com')
                    ->replyTo('info@medicineforworld.com.bd', 'Medicine For World')
                    ->sender('noreply@medicineforworld.com.bd', 'Medicine For World')
                    ->priority(1)
                    ->returnPath('noreply@medicineforworld.com.bd')
                    ->subject($data[0]['subject']);
            $message->from($data[0]['mailSenderEmail'], $data[0]['mailSenderName']);
        });


    }


    function emailreplace($email){

        $email = str_replace('@','[at]' , $email);
        $email = str_replace('.','[dot]' , $email);
        return $email;
    }

    function strip_except_english($str){

        // $str = preg_replace('/[^0-9A-Za-z\-]/', '', $str);
        $str = preg_replace('/\p{Han}+/u', '', $str);  // strip chinese
        $str = preg_replace('/[\x{0410}-\x{042F}]+.*[\x{0410}-\x{042F}]+/iu', '', $str);  // strip russian
        return $str;
    }

    function cacheRemove()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:cache');
        } catch (\Throwable $th) {
        }
    }




    // algorithms

    // prime numbers
    function isPrime($n)
    {
        // Corner case
        if ($n <= 1)
            return false;

        // Check from 2 to n-1
        for ($i = 2; $i < $n; $i++)
            if ($n % $i == 0)
                return false;

        return true;
    }

    function getFormattedDatetime($datetime)
    {
        return \Carbon\Carbon::parse($datetime)->format('Y-m-d H:i:s');
    }

    function getFormattedDate($datetime)
    {
        return \Carbon\Carbon::parse($datetime)->format('Y-m-d');
    }
    function convertYMDToForwardMDY($date)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format('m/d/Y');
    }


    function getToday()
    {
        return \Carbon\Carbon::now('+06:00')->format('Y-m-d');
    }
    function getTodayWStartTime()
    {
        return \Carbon\Carbon::now('+06:00')->startOfDay()->format('Y-m-d 00:00:00');
    }
    function getTodayWEndTime()
    {
        return \Carbon\Carbon::now('+06:00')->startOfDay()->format('Y-m-d 23:59:59');
    }
    function getThisWeekFirstDay()
    {
        return \Carbon\Carbon::now('+06:00')->subDays(6)->format('Y-m-d');
    }
    function getThisWeekFirstDayWStartTime()
    {
        return \Carbon\Carbon::now('+06:00')->subDays(6)->format('Y-m-d').' 00:00:00';
    }

    function getThisMonthFirstDay()
    {
        return \Carbon\Carbon::now('+06:00')->subDays(30)->format('Y-m-d');
    }
    function getThisMonthFirstDayWStartTime()
    {
        return \Carbon\Carbon::now('+06:00')->subDays(30)->format('Y-m-d').' 00:00:00';
    }



    function getTodayDateTime()
    {
        return \Carbon\Carbon::now('+06:00')->format('Y-m-d H:i:s');
    }

    function getNow()
    {
        return \Carbon\Carbon::now('+06:00');
    }

    function getTimeWithAddMinutes($minutes=0)
    {
        return \Carbon\Carbon::now('+06:00')->addMinute($minutes);
    }


    function getYesterday()
    {
        return \Carbon\Carbon::yesterday()->format('Y-m-d');
    }

    function getYesterdayWStartTime()
    {
        return \Carbon\Carbon::yesterday()->format('Y-m-d').' 00:00:00';
    }

    function getYesterdayWEndTime()
    {
        return \Carbon\Carbon::yesterday()->format('Y-m-d').' 23:59:59';
    }



    function getLast30DaysFirstDayExceptToday()
    {
        return \Carbon\Carbon::now('+06:00')->subDays(30)->format('Y-m-d');
    }

    function getLastNDaysFirstDayExceptToday($days)
    {
        return \Carbon\Carbon::now('+06:00')->subDays($days)->format('Y-m-d');
    }


    function getDatesFromARange($firstDay, $numberofdays, $order='asc')
    {
        $dates = [];
        $currentDate = carbondatetimeToYmd($firstDay);
        for ($i=0; $i < $numberofdays ; $i++)
        {
            $dates[$i] = $currentDate;
            $currentDate = date('Y-m-d', strtotime("+1 day", strtotime($dates[$i])));
        }
        if ($order='desc') {
            return array_reverse($dates);
        }
        return $dates;
    }

    function getDatesFromARangeSubtractDay($firstDay, $numberofdays, $order='asc')
    {
        $dates = [];
        $currentDate = $firstDay;
        for ($i=$numberofdays; $i>0 ; $i--)
        {
            $dates[$i] = $currentDate;
            $currentDate = date('Y-m-d', strtotime("-1 day", strtotime($dates[$i])));
        }

        if ($order='desc') {
            return array_reverse($dates);
        }
        return $dates;
    }

    function getSubtractDate($lastDate, $numberofdays)
    {
        $numberofdays = $numberofdays-1;
        $date = date('Y-m-d', strtotime("-".$numberofdays." day", strtotime($lastDate)));
        return $date;
    }

    function getAddDaysToDatetimeN($datetime, $daynumber)
    {
        return date('Y-m-d H:i:s', strtotime("+".$daynumber." day", strtotime($datetime)));
    }


    function getDatesDMYFromARange($firstDay, $numberofdays)
    {
        $dates = [];
        $currentDate = $firstDay;
        for ($i=0; $i < $numberofdays ; $i++)
        {
            $dates[$i] = YmdTodmY($currentDate);
            $currentDate = date('Y-m-d', strtotime("+1 day", strtotime($dates[$i])));
        }
        return $dates;
    }

    function getDatesFrom2Dates($date1, $date2, $order='asc')
    {
        $dates = [];
        if ($date2 > $date1)
        {
            $numberofdays = getNumberOfDaysFrom2Dates($date1, $date2)+1;
            $dates = getDatesFromARange($date1, $numberofdays);
        }

        if ($order='desc') {
            return array_reverse($dates);
        }

        return $dates;
    }

    function getNumberOfDaysFrom2Dates($fdate, $tdate)
    {
        $datetime1 = strtotime($fdate); // convert to timestamps
        $datetime2 = strtotime($tdate); // convert to timestamps
        $days = (int)(($datetime2 - $datetime1)/86400); // will give the difference in days , 86400 is the timestamp difference of a day
        return $days;
    }





    function array_flatten($array) {
        if (!is_array($array)) {
            return false;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, array_flatten($value));
            } else {
                $result = array_merge($result, array($key => $value));
            }
        }
        return $result;
    }

    function getDayIdWithDayFullName($day)
    {
        $day = strtolower($day);
        $dayId = 0;
        switch ($day) {
            case 'saturday':
                $dayId=1;
                break;
            case 'sunday':
                $dayId=2;
                break;
            case 'monday':
                $dayId=3;
                break;
            case 'tuesday':
                $dayId=4;
                break;
            case 'wednesday':
                $dayId=5;
                break;
            case 'thursday':
                $dayId=6;
                break;
            case 'friday':
                $dayId=7;
                break;
            default:
                $dayId=0;
        }

        return $dayId;
    }

    function getStatusString($status=0)
    {
        $statusstring = 'Inactive';
        if ($status==1) {
            $statusstring = 'Active';
        }
        return $statusstring;
    }


    // paginate
    // paginate

    function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Illuminate\Support\Collection ? $items : Illuminate\Support\Collection::make($items);

        $data = new Illuminate\Pagination\LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);

        return $data;
    }

    function getFormattedPaginatedArray($data)
    {
        return [
            'current_page' => $data->currentPage(),
            'total_pages' => $data->lastPage(),
            'previous_page_url' => $data->previousPageUrl(),
            'next_page_url' => $data->nextPageUrl(),
            'record_per_page' => $data->perPage(),
            'current_page_items_count' => $data->count(),
            'total_count' => $data->total(),
            'pagination_last_page' => $data->lastPage(),
        ];
    }


    // base 64 file related
    // base 64 file related
    // base 64 file related

    function getBase64FileExtension($base64_string)
    {
        $extension = explode('/', mime_content_type($base64_string))[1];
        return $extension;
    }

    function getBase64FileSize($base64_string)
    {
        $size = (int) (strlen(rtrim($base64_string, '=')) * 3 / 4);
        return $size/1024;
    }

    function getBase64FileSize_W_KB_MB($base64_string)
    {
        $size = (int) (strlen(rtrim($base64_string, '=')) * 3 / 4);

        $size = $size/1024; // in kb now

        if ( $size >= 1024 )
        {
            $size= ((int) ($size/1024)).' MB';
        }
        else{
            $size= $size.' KB';
        }

        return $size;
    }

    function base64_file_type_validation($base64_file_string='', $file_types_array=[])
    {
        if (strlen($base64_file_string)>100)
        {
            $extension = getBase64FileExtension($base64_file_string);
            if (!in_array($extension, $file_types_array))
            {
                return [0, 'Available file format '.implode(", ",$file_types_array)];
            }
            else{
                return [1, null];
            }
        }
        return [0, 'Invalid file string'];
    }

    function base64_file_size_validation($base64_file_string='', $max_file_size=[])
    {
        if (strlen($base64_file_string)>100)
        {
            $size = getBase64FileSize($base64_file_string);
            if ($size>$max_file_size)
            {
                return [0, 'Max file size '.$max_file_size.' KB exceeded!'];
            }
            else
            {
                return [1, null];
            }
        }
        return [0, 'Invalid file string'];
    }


    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = (strpos($string, $end, $ini) - $ini)<0 ? strlen($string) : 0;
        // dd($len);
        return substr($string, $ini, $len);
    }

    function compareData($newData=0, $oldData=1)
    {
        if ($oldData==0) {
            return 0;
        }
        return ($newData*100)/$oldData;
    }

    function convertMobileOperatorToSP($operator)
    {
        $sp = '';
        switch ($operator) {
            case "grameenphone":
              $sp = ['88013', '88017'];
              break;
            case "banglalink":
              $sp = ['88014', '88019'];
              break;
            case "robi":
              $sp = ['88018'];
              break;
            case "airtel":
                $sp = ['88016'];
                break;
            case "Taletalk":
                $sp = ['88015'];
                break;
            default:
              $sp = [];
        }
        return $sp;
    }



    if (!function_exists('csvExport')) {
        function csvExport($data, $filename)
        {
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename=' . $filename);

            $fp = fopen('php://output', 'w');

            function prettyString($string)
            {
                $val = str_replace("_", " ", $string);
                return ucwords($val);
            }

            $header = array_keys($data[0]);
            $header = array_map('prettyString', $header);

            fputcsv($fp, $header);
            foreach ($data as $row) {
                //$fp = fopen('php://output', 'a');
                fputcsv($fp, $row);
            }
            fclose($fp);
            exit;
        }
    }

    function getPrevMonthFirstDay()
    {
        return (new \Carbon\Carbon('first day of last month'))->format('Y-m-d');
    }

    function getPrevMonthLastDay()
    {
        return (new \Carbon\Carbon('last day of last month'))->format('Y-m-d');
    }

    if (!function_exists('formatCommonErrorLogMessage')) {
        function formatCommonErrorLogMessage($exception)
        {
            $logMessage = 'Error occured on File: ' . $exception->getFile() . ' on Line: ' . $exception->getLine() . ' due to: ' . $exception->getMessage();

            return $logMessage;
        }
    }

    if (!function_exists('writeToLog')) {
        function writeToLog($logMessage, $logType = 'error')
        {
            try {
                $allLogTypes = [ 'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug' ];

                $logType = strtolower($logType);

                if (in_array($logType, $allLogTypes)) {
                    \Log::$logType($logMessage);
                }
            } catch (\Exception $exception) {
                //
            }
        }
    }




    // ====================Masking=========================
    // ====================Masking=========================


    function mobileNumberMask($number)
    {
        return substr_replace($number, '****', -7, 4);
    }


    // sms masking
    function digitMask($sms)
    {
        return preg_replace('/[0-9\.]+/', '***', $sms);
    }

    // credit card
    function ccMasking($number, $maskingCharacter = 'X')
    {
        // $arr = getUniqueArrStr($number);
        if (isset($number) && strlen($number)>4)
        {
            return substr($number, 0, 6) . str_repeat($maskingCharacter, strlen($number) - 10) . substr($number, -4);
        }
        else{
            return $number ?? '';
        }
    }

    function masking_avoid($string)
    {
        $patterns = [];
        $patterns[] = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/'; // datetime
        $patterns[] = '/(?:2[0-3]|[01][0-9]):[0-5][0-9]/'; // 24 hour format
        $patterns[] = '/(?:1[012]|0[0-9]):[0-5][0-9]((am|pm)?)/i'; // 12 hour format

        $patterns[] = '/XXXXXX/i'; // after already credit card masking avoid


        $string_final = preg_replace($patterns, '', $string) ?? '';
        $string_final = preg_replace('!\s+!', ' ', $string_final); //Replacing multiple spaces with a single space
        // dd($string, $string_final);

        // dd(preg_match($patterns[2], ' 2022-03-07 17:37:21'));
        // dd(preg_match($patterns[2], ' 2022-03-07 17:37:21'));
        // dd($string);

        return $string_final;
    }

    function stringMaskingConverter_w_types($string, $types=[])
    {
        // dd($string);
        $string_m = masking_avoid($string);
        // dd($string_m);
        preg_match_all('!\d+!', $string_m, $matches);
        // preg_match_all('!\d+!', $string, $matches);
        // dd($matches);


        $arr = [];
        foreach ($matches as $value){
            $arr= $value;
        }
        $arr = array_unique($arr); // make unique array
        $arr = array_filter($arr,function($v){ return strlen($v) > 3; }); // Remove array element based on its character length <=3 , take only characters length > 3
        // dd($arr);


        usort($arr, function($a, $b)
        {
            return strlen($b) - strlen($a);
        });

        $new_string = $string;
        // dd($arr);

        foreach ($arr as $key=>$val)
        {
            if((strlen($val) > 19) && in_array('numeric_masking', $types))
            {
                $sms = $val;
                $new_val = digitMask($sms);
                $new_string = str_replace($val,$new_val,$string);
            }
            else if((strlen($val) >= 15 && strlen($val) <= 19 ) && in_array('credit_card', $types) )
            {
                $number = $val;
                $new_val = ccMasking($number);
                $string = $new_string;
                $new_string = str_replace($val,$new_val,$string);
            }else if((strlen($val) == 10 || strlen($val) == 11 || strlen($val) == 13) && in_array('mobile_masking', $types) )
            {
                $new_val = mobileNumberMask($val);
                $string = $new_string;
                $new_string = str_replace($val,$new_val,$string);
            }
            else if((strlen($val) < 11) && in_array('numeric_masking', $types) )
            {
                $sms = $val;
                $new_val = digitMask($sms);
                $string = $new_string;
                $new_string = str_replace($val,$new_val,$string);
            }
            // dd($new_string);
        }

        return $new_string;
    }


    function stringMaskingConverter($string)
    {
        $string_m = masking_avoid($string);
        preg_match_all('!\d+!', $string_m, $matches);

        $arr = [];
        foreach ($matches as $value){
            $arr= $value;
        }

        usort($arr, function($a, $b) {
            return strlen($b) - strlen($a);
        });

        $new_string = $string;

        foreach ($arr as $key=>$val){
            if(strlen($val) > 19){
                $sms = $val;
                $new_val = digitMask($sms);
                $new_string = str_replace($val,$new_val,$string);
            }
            else if(strlen($val) == 15 || strlen($val) == 16 || strlen($val) == 17 || strlen($val) == 18 || strlen($val) == 19 ){
                $number = $val;
                $new_val = ccMasking($number);
                $string = $new_string;
                $new_string = str_replace($val,$new_val,$string);
            }else if(strlen($val) == 11 || strlen($val) == 13){
                $new_val = mobileNumberMask($val);
                $string = $new_string;
                $new_string = str_replace($val,$new_val,$string);
            }
            else if(strlen($val) < 11){
                $sms = $val;
                $new_val = digitMask($sms);
                $string = $new_string;
                $new_string = str_replace($val,$new_val,$string);
            }

        }

        return $new_string;
    }
    // ====================Masking=========================
    // ====================Masking=========================



    // ====================DB related=========================
    // ====================DB related=========================



    function getExistingDBDetails($preset_db_id, $db_server_id, $selected=1){
        $presetDBDetailsList = PresetDBAccessDetails::where('preset_db_id', $preset_db_id)
                                ->where('db_server_id', $db_server_id)
                                ->selectRaw("table_name")
                                ->groupBy('table_name')
                                ->get();

        $data_details = [];

        if (count($presetDBDetailsList)>0) {
            foreach ($presetDBDetailsList as $item)
            {
                $columns_data = PresetDBAccessDetails::where('preset_db_id', $preset_db_id)
                                    ->where('db_server_id', $db_server_id)
                                    ->where('table_name', $item->table_name)
                                    ->select('id','table_name', 'column_name', 'data_type', 'data_type_length', 'limit')
                                    ->get();


                foreach ($columns_data as $key => $row)
                {
                    $db_masking_type_ids = DB::table('preset_db_acc_dtls_masking_types')->where('preset_db_access_detail_id', $row->id)->pluck('db_masking_type_id')->toArray();
                    $columns_data[$key]['db_masking_type_ids'] = $db_masking_type_ids;
                }

                $data_details[] = [
                    'table_name' => $item->table_name,
                    'limit' => $columns_data[0]->limit,
                    'columns' => $columns_data,
                ];
            }
        }

        return $data_details;
    }

    // ====================DB related=========================
    // ====================DB related=========================

    function token_lifetime_update($user)
    {
        $expires = getTimeWithAddMinutes(config('app.session_lifetime'));
        $tokens_data = OauthAccessToken::where('user_id', $user->id)
                        ->where('revoked', 0)
                        ->where('expires_at', '>', getNow())
                        ->get();

        $tokens_data->map(function ($q) use($expires) {
                            return $q->update([
                                'expires_at' => $expires
                            ]);
                        });

    }


    function token_management($request, $user)
    {
        $expires = getTimeWithAddMinutes(config('app.session_lifetime'));
        $tokens_data = OauthAccessToken::where('user_id', $user->id)
                        ->where('revoked', 0)
                        ->where('expires_at', '>', getNow())
                        ->get();

        $tokens_data->map(function ($q) use($expires) {
                            return $q->update([
                                'expires_at' => $expires
                            ]);
                        });

        $token = explode(' ', $request->header('Authorization'))[1];

        return [
            'token' => $token,
            'expires' => getFormattedDatetime($expires),
        ];
    }


    function rsa_encrypt($data='')
    {
        if (isset($data)) {
            try {
                $pubfile = public_path('/rsa/production-data-public.key') ;
                $prifile = public_path('/rsa/production-data-private.key') ;
                $rsa = new RSA($pubfile, $prifile);

                $encrypted = $rsa->encrypt($data);

                return $encrypted;
            } catch (\Exception $e) {
                return '';
            }
        }
        return '';
    }

    function rsa_decrypt($data='')
    {
        if (isset($data)) {
            try {
                $pubfile = public_path('/rsa/production-data-public.key') ;
                $prifile = public_path('/rsa/production-data-private.key') ;
                $rsa = new RSA($pubfile, $prifile);


                $decrypted = $rsa->decrypt($data);

                return $decrypted;
            } catch (\Exception $e) {
                return '';
            }
        }
        return '';
    }


    if (!function_exists("processOrderBy")) {

        function processOrderBy($default_sort_field=null, $default_sort_order='ASC', $sort_table=null, $sort_field=null, $sort_order=null)
        {
            if(isset($sort_table) && isset($sort_field))
            {
                return [$sort_table.'.'.$sort_field, $sort_order ?? $default_sort_order];
            }
            else if(isset($sort_field))
            {
                return [$sort_field, $sort_order ?? $default_sort_order];
            }
            else if (isset($default_sort_field))
            {
                return [$default_sort_field, $sort_order ?? $default_sort_order];
            }
            else{
                return [null, null];
            }
        }
    }

    if (!function_exists("formatErrors")) {
        function formatErrors($validator)
        {
            $errors = [];
            foreach ($validator->errors()->messages() as $field => $messages) {
                foreach ($messages as $message) {
                    $errors[] = [
                        'field' => $field,
                        'message' => $message,
                    ];
                }
            }
            return $errors;
        }
    }


    if (!function_exists("convertMsisdnToOperator")) {
        function convertMsisdnToOperator($msisdn)
        {
            // If the MSISDN length is greater than 13 characters, return "Grameenphone"
            if (strlen($msisdn) > 13) {
                return 'Grameenphone';
            }

            $prefixes = [
                'grameenphone' => ['88013', '88017'],
                'banglalink'   => ['88014', '88019'],
                'robi'         => ['88018'],
                'airtel'       => ['88016'],
                'teletalk'     => ['88015']
            ];

            foreach ($prefixes as $name => $prefixList) {
                foreach ($prefixList as $prefix) {
                    if (strpos($msisdn, $prefix) === 0) {
                        return ucfirst($name); // Return operator name with the first letter capitalized
                    }
                }
            }

            return 'Unknown Operator'; // If no match is found
        }
    }

    if (!function_exists("convertMsisdnToOperatorId")) {
        function convertMsisdnToOperatorId($msisdn)
        {
            // If the MSISDN length is greater than 13 characters, return 1 (Grameenphone)
            if (strlen($msisdn) > 13) {
                return 1;
            }

            // Map each operator to a number
            $prefixes = [
                1 => ['88013', '88017'], // Grameenphone
                2 => ['88014', '88019'], // Banglalink
                3 => ['88018'],          // Robi
                4 => ['88016'],          // Airtel
                5 => ['88015']           // Teletalk
            ];

            // Iterate over the prefixes to find a match
            foreach ($prefixes as $number => $prefixList) {
                foreach ($prefixList as $prefix) {
                    if (strpos($msisdn, $prefix) === 0) {
                        return $number; // Return the corresponding number
                    }
                }
            }

            return 0; // If no match is found, return 0 (Unknown Operator)
        }
    }

    if (!function_exists("convertDotNetDateToYmd")) {
        function convertDotNetDateToYmd($dotNetDate) {
            // Extract the timestamp using a regular expression
            if (preg_match('/Date\((\d+)\)/', $dotNetDate, $matches)) {
                $timestampInMilliseconds = $matches[1]; // Extracted timestamp
                $timestampInSeconds = $timestampInMilliseconds / 1000; // Convert to seconds

                // Format the timestamp as Y-m-d
                return date('Y-m-d', $timestampInSeconds);
            }

            // Return null if input is invalid
            return null;
        }
    }


    // file uploading related
    // file uploading related
    if (!function_exists('getFileName')) {

        function getFileName($filename = '')
        {
            $filename = strtolower($filename);
            $filename = preg_replace('/[^0-9A-Za-z\-]/', '_', $filename);
            return $filename;
        }
    }

    if (!function_exists('getProcessFile')) {

        function getProcessFile($file, $file_name = '', $file_location='')
        {
            $timestamp = getTimeStamp();
            $filename = getFileName($file_name) . '_' . $timestamp . '.' . $file->getClientOriginalExtension();
            $file_path = '/' . $file_location . $filename;
            $file->move($file_location, $filename);

            return [$file_path];
        }
    }
    // file uploading related
    // file uploading related


    function preprocess_text_for_phone_number_bd($text) {
        // *. Remove leading/trailing whitespace
        $text = trim($text);

        // *. Extract only digits 0-9
        $text = preg_replace('/[^0-9]/', '', $text);

        // *. get last 11 characters
        $text = '01'.substr($text, -9);

        return $text;
    }

    if (!function_exists('isset_array')) {
        function isset_array($data = null)
        {
            if (isset($data)) {
                if (is_array($data)) {
                    if (count($data) > 0) {
                        return true;
                    }
                }
            }
            return false;
        }
    }

?>
