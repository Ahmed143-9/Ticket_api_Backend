<?php

namespace App\Contracts;
use Illuminate\Http\Request;

interface CartInterface {
    public function filterData(Request $request);
    public function paginate(Request $request);
    public function show( $id );
    public function update(Request $request );

    public function paymentMethodsData(Request $request);
}

