<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiResponser;

class PaymentMethodController extends Controller {

    use ApiResponser;
    public function getPaymentMethodTypes() {
        $data = DB::table( 'payment_method_type' )->orderBy( 'id' )->get();
        return $this->set_response( $data,  200, 'success', [ 'Data list' ] );
    }

}
