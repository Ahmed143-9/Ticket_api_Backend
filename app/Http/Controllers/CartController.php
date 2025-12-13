<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Contracts\CartInterface;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Modules\Cart\CartUpdateRequest;

class CartController extends Controller
{
    use ApiResponser;

    protected $cart;

    public function __construct(CartInterface $cart)
    {
        $this->cart = $cart;
    }

    public function listPaginate(Request $request)
    {
        $data = $this->cart->paginate($request);
        return $this->set_response($data,  200, 'success', ['Data list']);
    }

    public function singleData($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|numeric|exists:order,id',
        ]);

        if ($validator->fails()) {
            return $this->set_response(null, 422, 'error', $validator->errors()->all());
        }

        $data = $this->cart->show($id);

        return $this->set_response($data, 200, 'success', ['Single data']);
    }


    public function update(CartUpdateRequest $request)
    {
        try {
            $data = $this->cart->update($request);

            return $this->set_response($data, 200, 'success', ['Data Updated successfully']);
        } catch (\Exception $e) {
            $logMessage = formatCommonErrorLogMessage($e);
            writeToLog($logMessage, 'debug');
            return $this->set_response(null,  422, 'error', ['Something went wrong. Please try again later!']);
        }
    }

    public function filterData( Request $request ) {
        $data = $this->cart->filterData($request);
        return $this->set_response($data,  200, 'success', ['filter list']);
    }


    public function getPaymentMethodsData(Request $request)
    {
        $data = $this->cart->paymentMethodsData($request);
        return $this->set_response($data,  200, 'success', ['Data list']);
    }
}
