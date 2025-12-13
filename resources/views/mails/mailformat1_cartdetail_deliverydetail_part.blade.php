        
    <table style="border: 1px solid gray; text-align: center; border-collapse: collapse;" >
        <caption style="font-weight: bold;  font-size: 20px; color: green;">Order Details</caption>
        <thead >
            <tr >
                <th style="border: 1px solid grey;">Products</th>
                <th style="border: 1px solid grey;">Qty</th>
                <th style="border: 1px solid grey;">Total {{ ' ('.$cartData->currency.')' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cartdetailsData->where('cartId', $cartData->cartId) as $cartdetail)
                <tr>
                <td style="border: 1px solid grey; text-align: left; padding-left: 5px;">
                        {{  $genericpacksizes_with_customer_price_Data->where('genericBrandId',  $cartdetail->genericBrandId)->pluck('genericBrand')->first().' ('.
                        $genericpacksizes_with_customer_price_Data->where('genericPackSizeId',  $cartdetail->genericPackSizeId)->pluck('genericName')->first().' '.
                        $genericpacksizes_with_customer_price_Data->where('genericPackSizeId',  $cartdetail->genericPackSizeId)->pluck('genericStrength')->first().'), '.
                        $genericpacksizes_with_customer_price_Data->where('genericPackSizeId',  $cartdetail->genericPackSizeId)->pluck('genericPackSize')->first().'\'s '. $genericpacksizes_with_customer_price_Data->where('genericPackSizeId',  $cartdetail->genericPackSizeId)->pluck('packType')->first().' | '.
                        $genericpacksizes_with_customer_price_Data->where('genericPackSizeId',  $cartdetail->genericPackSizeId)->pluck('dosageForm')->first().' | '.
                        $genericpacksizes_with_customer_price_Data->where('genericPackSizeId',  $cartdetail->genericPackSizeId)->pluck('genericCompany')->first() }}
                
                </td>
                <td style="border: 1px solid grey;">{{ $cartdetail->qty }}</td>
                <td style="border: 1px solid grey; text-align: left; ">{!! $countryData->where('currency', $cartData->currency)->pluck('hexcode')->first() !!}  {{ $cartdetail->subtotal * ( $countryData->where('currency', $cartData->currency)->pluck('usdToCurrencyRate')->first() )  }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot >

        <tr>
            <th style="border: 1px solid grey; text-align: left; padding-left: 5px;">Sub-Total</th>
            <th style="border: 1px solid grey;">{{ $cartData->totalQty }}</th>
            <th style="border: 1px solid grey; text-align: left; ">{!! $countryData->where('currency', $cartData->currency)->pluck('hexcode')->first() !!}  {{ $cartData->subTotalAmount  }}</th>
        </tr>
        <tr>
            <th style="border: 1px solid grey; text-align: left; padding-left: 5px;">Discount</th>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey; text-align: left; ">{!! $countryData->where('currency', $cartData->currency)->pluck('hexcode')->first() !!}  {{ $cartData->discount }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid grey; text-align: left; padding-left: 5px;">Tax</th>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey; text-align: left; ">{!! $countryData->where('currency', $cartData->currency)->pluck('hexcode')->first() !!}  {{ $cartData->tax }}</td>
        </tr>
        <tr>
            <th style="border: 1px solid grey; text-align: left; padding-left: 5px;">Shipping Cost</th>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey; text-align: left; ">{!! $countryData->where('currency', $cartData->currency)->pluck('hexcode')->first() !!}  {{ $cartData->shippingAmount}}</td>
        </tr>

        <tr>
            <th style="border: 1px solid grey; text-align: left; padding-left: 5px;">Transaction Fee</th>
            <td style="border: 1px solid grey;"></td>
            <td style="border: 1px solid grey; text-align: left; ">{!! $countryData->where('currency', $cartData->currency)->pluck('hexcode')->first() !!}  {{ $cartData->transactionFeeAmount }} </td>
        </tr>

        <tr>
            <th style="border: 1px solid grey; text-align: left; padding-left: 5px;">Net Payable</th>
            <td style="border: 1px solid grey;"></td>
            <th style="border: 1px solid grey;  text-align: left; ">{!! $countryData->where('currency', $cartData->currency)->pluck('hexcode')->first() !!}  {{ $cartData->totalAmount }}</th>
        </tr>

        </tfoot>
    </table>


<br>
<br>


    <table style="border: 1px solid gray;  border-collapse: collapse;" >
        <caption style="font-weight: bold;  font-size: 20px; color: green;">Delivery Details</caption>
        <tbody>
            <tr>
              <th style="border: 1px solid grey;  text-align: left; padding-left: 5px;">Name (In English)</th>
              <td style="border: 1px solid grey; text-align: left; padding-left: 5px;">{{ $cartData->takingFor }}</td>
            </tr>

            <tr>
              <th style="border: 1px solid grey;  text-align: left; padding-left: 5px;">Name (In Local Language)</th>
              <td style="border: 1px solid grey; text-align: left; padding-left: 5px;">{{ $cartData->takingForLocalLang }}</td>
            </tr>


            <tr>
              <th style="border: 1px solid grey;  text-align: left; padding-left: 5px;">Phone</th>
              <td style="border: 1px solid grey; text-align: left; padding-left: 5px;">{{ $cartData->phoneCode.$cartData->phone }}</td>
            </tr>

            <tr>
              <th style="border: 1px solid grey;  text-align: left; padding-left: 5px;">Phone 2</th>
              <td style="border: 1px solid grey; text-align: left; padding-left: 5px;">{{ $cartData->phonenumber2 }}</td>
            </tr>


            <tr>
              <th style="border: 1px solid grey;  text-align: left; padding-left: 5px;">House/Street Info (In English)</th>
              <td style="border: 1px solid grey; text-align: left; padding-left: 5px;">{{ $cartData->streethouse }}</td>
            </tr>

            <tr>
              <th style="border: 1px solid grey;  text-align: left; padding-left: 5px;">House/Street Info (In Local Language)</th>
              <td style="border: 1px solid grey; text-align: left; padding-left: 5px;">{{ $cartData->streethouseLocalLang }}</td>
            </tr>



            <tr>
              <th style="border: 1px solid grey;  text-align: left; padding-left: 5px;">Country</th>
              <td style="border: 1px solid grey; text-align: left; padding-left: 5px;">{{ $countryData->where('countryId', $cartData->countryId )->pluck('country')->first() }}</td>
            </tr>


            <tr>
              <th style="border: 1px solid grey;  text-align: left; padding-left: 5px;">City (In English)</th>
              <td style="border: 1px solid grey; text-align: left; padding-left: 5px;">{{ $cartData->city }}</td>
            </tr>

            <tr>
              <th style="border: 1px solid grey;  text-align: left; padding-left: 5px;">City (In Local Language)</th>
              <td style="border: 1px solid grey; text-align: left; padding-left: 5px;">{{ $cartData->cityLocalLang }}</td>
            </tr>

            

            <tr>
              <th style="border: 1px solid grey;  text-align: left; padding-left: 5px;">Postal Code</th>
              <td style="border: 1px solid grey; text-align: left; padding-left: 5px;">{{ $cartData->postalCode }}</td>
            </tr>

            

            <tr>
              <th style="border: 1px solid grey;  text-align: left; padding-left: 5px;">Delivery Method</th>
              <td style="border: 1px solid grey; text-align: left; padding-left: 5px;">
                    {{ $deliverymethodsData->where('deliveryMethodId', $cartData->deliveryMethodId )->pluck('deliveryMethod')->first() }}
              </td>
            </tr>

            @if (DB::table('cartapproves')->where('cartId',  $cartData->cartId)->pluck('paymentMethodId')->first())
              <tr>
                <th style="border: 1px solid grey;  text-align: left; padding-left: 5px;">Payment Method</th>
                <td style="border: 1px solid grey; text-align: left; padding-left: 5px;">
                      {{ DB::table('paymentmethod')->where('paymentMethodId',   DB::table('cartapproves')->where('cartId',  $cartData->cartId)->pluck('paymentMethodId')->first())->pluck('paymentMethod')->first() }}
                </td>
              </tr>
            @endif


        </tbody>
    </table>