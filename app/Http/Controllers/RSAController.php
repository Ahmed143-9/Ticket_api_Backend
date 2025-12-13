<?php
namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Libraries\RSA;

class RSAController extends Controller
{
    use ApiResponser;

    public function encrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'data' => 'required',
            ],
            [
                'data.required' => 'A data is required to encrypt',
            ]
        );
        if ($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        $pubfile = public_path('/rsa/production-data-public.key') ;
        $prifile = public_path('/rsa/production-data-private.key') ;
        $rsa = new RSA($pubfile, $prifile);


        $encrypted = $rsa->encrypt($request->data);

        $result = [
            'original' => $request->data,
            'encrypted' => $encrypted,
        ];

        return $this->set_response($result, 200,'success', ['Encryption']);
    }


    public function decrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'data' => 'required',
            ],
            [
                'data.required' => 'A encrypted data is required to decrypt',
            ]
        );
        if ($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }

        $pubfile = public_path('/rsa/production-data-public.key') ;
        $prifile = public_path('/rsa/production-data-private.key') ;
        $rsa = new RSA($pubfile, $prifile);


        $decrypted = $rsa->decrypt($request->data);

        $result = [
            'encrypted' => $request->data,
            'decrypted' => $decrypted,
        ];

        return $this->set_response($result, 200,'success', ['Decryption']);
    }



}

