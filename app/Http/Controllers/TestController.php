<?php
namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Validator;


class TestController extends Controller
{
    use ApiResponser;

    public function preg_match(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pattern' => 'required',
            'string' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->set_response(null, 422, 'failed', $validator->errors()->all());
        }


        dd( $request->string, $request->pattern, preg_match($request->pattern, $request->string));

    }


}



