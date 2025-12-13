<?php

namespace App\Traits;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use GuzzleHttp\Client;
use Exception;

trait ApiService {

    // public function postCall( $path = '', $form_params = [], $token = '' )
    // {
    //     try
    // {
    //         $client = new \GuzzleHttp\Client();
    //         $response = $client->request( 'POST', config( 'app.base_api_url' ).$path, [
    //             'headers' => [
    //                 'Authorization'     => 'Bearer '.$token
    // ],
    //             'form_params' => $form_params
    // ] );

    //         $data =  json_decode( json_encode( json_decode( ( $response->getBody() )->getContents(), true ) ) );
    //         return $data;
    //     }
    //     catch( Exception $e )
    // {
    //         dd( $e->getResponse()->getBody()->getContents() );
    //         throw new Exception( $e->getResponse()->getBody()->getContents() );
    //         // return $e->getMessage();
    //     }
    // }

    public function postCall( $path = '', $form_params = [], $token = '' ) {
        $data = null;
        $curl = curl_init();
        curl_setopt_array( $curl, array(
            CURLOPT_URL => config( 'app.base_api_url' ).$path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode( $form_params ),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                'accept: */*',
                'accept-language: en-US,en;q=0.8',
                'content-type: application/json',
                'Authorization: Bearer '.$token ?? '' //$request->session()->put( 'token', 'token' );
            ),
        ) );

        $response = curl_exec( $curl );
        // dd( $response );
        $err = curl_error( $curl );

        curl_close( $curl );

        if ( $err ) {
            echo 'cURL Error #:' . $err;
        } else {
            $data = ( json_decode( $response ) );
        }

        return $data;
    }

    private function post( $request, $url, $body = [] ) {

        $data  = null;
        $curl = curl_init();

        curl_setopt_array( $curl, array(
            CURLOPT_URL => env( 'BASE_URL' ).'/'.$url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode( $body ),
            CURLOPT_HTTPHEADER => array(
                // Set here requred headers
                'accept: */*',
                'accept-language: en-US,en;q=0.8',
                'content-type: application/json',
                'Authorization: Bearer '.$request->session()->get( 'token' ) ?? '' //$request->session()->put( 'token', 'token' );
            ),
        ) );

        $response = curl_exec( $curl );
        $err = curl_error( $curl );

        curl_close( $curl );

        if ( $err ) {
            echo 'cURL Error #:' . $err;
        } else {
            $data = ( json_decode( $response ) );
        }

        return $data;
    }

    //     public function getCurl( $url, $body = [] ) {
    //         $data  = null;

    //         $curl = curl_init();

    //         curl_setopt_array( $curl, array(
    //             CURLOPT_URL => $url.'/?'.http_build_query( $body ),
    //             CURLOPT_RETURNTRANSFER => true,
    //             CURLOPT_ENCODING => '',
    //             CURLOPT_MAXREDIRS => 10,
    //             CURLOPT_TIMEOUT => 60,
    //             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //             CURLOPT_CUSTOMREQUEST => 'GET',
    //             CURLOPT_POSTFIELDS => '',
    //             CURLOPT_HTTPHEADER => array(
    //                 'Content-Type: application/json',
    // //                'Authorization: Bearer '.$request->session()->get( 'token' ) ?? ''//$request->session()->put( 'token', 'token' );
    // ),
    // ) );
    //         $response = curl_exec( $curl );
    //         $err = curl_error( $curl );
    //         curl_close( $curl );

    //         if ( $err ) {
    //             echo 'cURL Error #:' . $err;
    //             \Log::error( 'message'. $err );
    //         } else {
    //             $data = ( json_decode( $response ) );
    //         }

    //         return $data;
    //     }

    public function getCurl( $url, $body = [] ) {
        $data = null;

        // Initialize cURL session
        $curl = curl_init();

        // Construct the full URL with query parameters
        $fullUrl = $url . '/?' . http_build_query( $body );
        // dd($fullUrl);

        // Set cURL options
        curl_setopt_array( $curl, array(
            CURLOPT_URL => $fullUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => '',
            // CURLOPT_HTTPHEADER => array(
            //     'Content-Type: application/json',
            // ),
        ) );

        // Execute the cURL request
        $response = curl_exec( $curl );

        // Check for errors
        $err = curl_error( $curl );

        // Close cURL session
        curl_close( $curl );

        // Handle response or error
        if ( $err ) {
            echo 'cURL Error #: ' . $err;
            error_log( 'cURL Error: ' . $err );
            // Log the error
        } else {
            $data = json_decode( $response );
        }

        return $data;
    }

}

