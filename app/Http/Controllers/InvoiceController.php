<?php

namespace App\Http\Controllers;

use App\Models\MFSType;
use App\Models\CompanyInfo;
use App\Enum\PaginationEnum;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\PaymentAccount;
use App\Models\WintextInvoice;
use App\Models\PaymentMethodType;
use App\Models\WintextSupportData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\WintextInvoiceDetail;
use Illuminate\Support\Facades\Http;
use App\Models\WintextInvoicePaymentInstructionDetails;
use App\Http\Requests\Modules\WintextInvoice\WintextInvoiceCreateRequest;
use App\Http\Requests\Modules\WintextInvoice\WintextInvoiceUpdateRequest;

class InvoiceController extends Controller {
    use ApiResponser;

    public function getSupportData( Request $request ) {
        $wintext_support_data = WintextSupportData::get();
        $payment_accounts = PaymentAccount::with( 'payment_method_type', 'mfs_type', 'pmnt_rcv_bank', 'application' )->get();
        $company_infos = CompanyInfo::selectRaw('name as label, id as value')->orderBy('name', 'asc')->distinct()->get();
        $payment_method_types = PaymentMethodType::selectRaw('payment_method_type as label, id as value')->orderBy('id', 'asc')->distinct()->get();
        $mfs_types = MFSType::selectRaw('mfs_name as label, id as value')->orderBy('id', 'asc')->distinct()->get();


        return $this->set_response( [
            'payment_accounts' => $payment_accounts,
            'wintext_support_data' => $wintext_support_data,
            'company_infos'    => $company_infos,
            'payment_method_types'    => $payment_method_types,
            'mfs_types'    => $mfs_types,
        ], 200, 'success', [ 'Data' ] );
    }

    public function filterData()
    {
        $invoice_numbers = WintextInvoice::selectRaw('invoice_number as label, invoice_number as value')->orderBy('invoice_number', 'asc') ->distinct()->get();
        $client_names = WintextInvoice::selectRaw('client_name as label, client_name as value')->orderBy('client_name', 'asc')->distinct()->get();
        $company_infos = CompanyInfo::selectRaw('name as label, id as value')->orderBy('name', 'asc')->distinct()->get();

        $data = [
            'invoice_numbers' => $invoice_numbers,
            'client_names'    => $client_names,
            'company_infos'    => $company_infos,
        ];

        return $this->set_response($data, 200, 'success', ['Filter list']);
    }


    public function listPaginate( Request $request ) {

        $data = WintextInvoice::
                when(request()->filled('invoice_number'), function ($query) {
                    $query->where("invoice_number",  'like', '%' . request("invoice_number") . '%');
                })
                ->when(request()->filled('client_name'), function ($query) {
                    $query->where("client_name",  'like', '%' . request("client_name") . '%');
                })
                ->when(request()->filled('date_from'), function ($query) {
                    $query->whereDate('billing_date', '>=', request('date_from'));
                })
                ->when(request()->filled('date_to'), function ($query) {
                    $query->whereDate('billing_date', '<=', request('date_to'));
                })
                ->orderByDesc( 'id' );
        $data = $data->paginate(PaginationEnum::$DEFAULT);
        $data = [
            'paginator' => getFormattedPaginatedArray($data),
            'data' => $data->items(),
        ];

        return $this->set_response( $data, 200, 'success', [ 'Data' ] );
    }



    public function create( WintextInvoiceCreateRequest $request ) {
        DB::beginTransaction();
        try {
            $wintext_invoice = WintextInvoice::create( [
                'client_id' => $request->client_id,
                'invoice_number' => $request->invoice_number,
                'kam' => $request->kam,
                'prepared_by' => $request->prepared_by,
                'received_by' => $request->received_by,
                'company_id' => $request->company_id,
                'billing_date' => $request->billing_date,
                'client_name' => $request->client_name,
                'client_address' => $request->client_address,
                'billing_attention' => $request->billing_attention,
                'billing_attention_phone' => $request->billing_attention_phone,
                'subtotal' => $request->subtotal,
                'total' => $request->total,
                'amount_in_words' => $request->amount_in_words,
                'note' => $request->note,
                'payment_instructions' => $request->payment_instructions,
                'billing_start_date' => $request->billing_start_date,
                'billing_end_date' => $request->billing_end_date,
                'contract_no' => $request->contract_no,
                'vat' => $request->vat,
                'mushak_file_path' => $request->mushak_file_path,
                'bin' => $request->bin,
                'section' => $request->section,
                'client_email' => $request->client_email,

                'created_at' => getNow(),
                'created_by' => auth()->user()->id,
            ] );

            if(isset_array($request->wintext_invoice_dtl))
            {
                $wintext_invoice_dtl = [];
                foreach ($request->wintext_invoice_dtl as $key => $value)
                {
                    $wintext_invoice_dtl[] = [
                        'wintext_invoice_id' => $wintext_invoice['id'] ?? null,

                        'description' => $value[ 'description' ] ?? null,
                        'sms_qty' => $value[ 'sms_qty' ] ?? null,
                        'unit_price' => $value[ 'unit_price' ] ?? null,
                        'start_time' => $value[ 'start_time' ] ?? null,
                        'end_time' => $value[ 'end_time' ] ?? null,
                        'total' => $value[ 'total' ] ?? null,

                        'created_at' => getNow(),
                        'created_by' => auth()->user()->id,
                    ];
                }
                WintextInvoiceDetail::insert($wintext_invoice_dtl);
            }

            if(isset_array($request->wintext_inv_pmnt_instr_dtl))
            {
                $wintext_inv_pmnt_instr_dtl = [];
                foreach ($request->wintext_inv_pmnt_instr_dtl as $key => $value)
                {
                    $wintext_inv_pmnt_instr_dtl[] = [
                        'wintext_invoice_id' => $wintext_invoice['id'] ?? null,

                        'payment_account_id'=> $value['payment_account_id'] ?? null,
                        'payment_method_type_id'=> $value['payment_method_type_id'] ?? null,
                        'receiver_name'=> $value['receiver_name'] ?? null,
                        'pmnt_rcv_bank'=> $value['pmnt_rcv_bank'] ?? null,
                        'pmnt_receive_acc'=> $value['pmnt_receive_acc'] ?? null,
                        'pmnt_rcv_branch'=> $value['pmnt_rcv_branch'] ?? null,
                        'pmnt_rcv_rn'=> $value['pmnt_rcv_rn'] ?? null,
                        'mfs_type_id'=> $value['mfs_type_id'] ?? null,
                        'note'=> $value['note'] ?? null,
                        'txn_charge'=> $value['txn_charge'] ?? null,
                        'txn_charge_text'=> $value['txn_charge_text'] ?? null,
                        'merchant_type'=> $value['merchant_type'] ?? null,

                        'created_at' => getNow(),
                        'created_by' => auth()->user()->id,
                    ];
                }
                WintextInvoicePaymentInstructionDetails::insert($wintext_inv_pmnt_instr_dtl);
            }

            DB::commit();

            $data = WintextInvoice::with([
                'wintext_invoice_dtl', 'wintext_inv_pmnt_instr_dtl', 'company_info'

            ])
            ->where('id', $wintext_invoice['id'])->first();

            return [$data,  200,'success', ['Data Created']];
        } catch ( \Exception $e ) {
            DB::rollback();
            $logMessage = formatCommonErrorLogMessage($e);
            writeToLog($logMessage, 'debug');
            return [null,  422,'error', ['Something went wrong. Please try again later!']];
        }
    }

    public function update( WintextInvoiceUpdateRequest $request ) {
        DB::beginTransaction();
        try {
            $wintext_invoice = WintextInvoice::findOrFail($request->id);

            $wintext_invoice->update([
                'client_id' => $request->client_id,
                'invoice_number' => $request->invoice_number,
                'kam' => $request->kam,
                'prepared_by' => $request->prepared_by,
                'received_by' => $request->received_by,
                'company_id' => $request->company_id,
                'billing_date' => $request->billing_date,
                'client_name' => $request->client_name,
                'client_address' => $request->client_address,
                'billing_attention' => $request->billing_attention,
                'billing_attention_phone' => $request->billing_attention_phone,
                'subtotal' => $request->subtotal,
                'total' => $request->total,
                'amount_in_words' => $request->amount_in_words,
                'note' => $request->note,
                'payment_instructions' => $request->payment_instructions,
                'billing_start_date' => $request->billing_start_date,
                'billing_end_date' => $request->billing_end_date,
                'contract_no' => $request->contract_no,
                'vat' => $request->vat,
                'mushak_file_path' => $request->mushak_file_path,
                'bin' => $request->bin,
                'section' => $request->section,
                'client_email' => $request->client_email,

                'updated_at' => getNow(),
                'updated_by' => auth()->user()->id,
            ] );

            WintextInvoiceDetail::where('wintext_invoice_id', $request->id)->delete();
            if(isset_array($request->wintext_invoice_dtl))
            {
                $wintext_invoice_dtl = [];
                foreach ($request->wintext_invoice_dtl as $key => $value)
                {
                    $wintext_invoice_dtl[] = [
                        'wintext_invoice_id' => $wintext_invoice['id'] ?? null,

                        'description' => $value[ 'description' ] ?? null,
                        'sms_qty' => $value[ 'sms_qty' ] ?? null,
                        'unit_price' => $value[ 'unit_price' ] ?? null,
                        'start_time' => $value[ 'start_time' ] ?? null,
                        'end_time' => $value[ 'end_time' ] ?? null,
                        'total' => $value[ 'total' ] ?? null,

                        'created_at' => getNow(),
                        'created_by' => auth()->user()->id,
                    ];
                }
                WintextInvoiceDetail::insert($wintext_invoice_dtl);
            }

            WintextInvoicePaymentInstructionDetails::where('wintext_invoice_id', $request->id)->delete();
            if(isset_array($request->wintext_inv_pmnt_instr_dtl))
            {
                $wintext_inv_pmnt_instr_dtl = [];
                foreach ($request->wintext_inv_pmnt_instr_dtl as $key => $value)
                {
                    $wintext_inv_pmnt_instr_dtl[] = [
                        'wintext_invoice_id' => $wintext_invoice['id'] ?? null,

                        'payment_account_id'=> $value['payment_account_id'] ?? null,
                        'payment_method_type_id'=> $value['payment_method_type_id'] ?? null,
                        'receiver_name'=> $value['receiver_name'] ?? null,
                        'pmnt_rcv_bank'=> $value['pmnt_rcv_bank'] ?? null,
                        'pmnt_receive_acc'=> $value['pmnt_receive_acc'] ?? null,
                        'pmnt_rcv_branch'=> $value['pmnt_rcv_branch'] ?? null,
                        'pmnt_rcv_rn'=> $value['pmnt_rcv_rn'] ?? null,
                        'mfs_type_id'=> $value['mfs_type_id'] ?? null,
                        'note'=> $value['note'] ?? null,
                        'txn_charge'=> $value['txn_charge'] ?? null,
                        'txn_charge_text'=> $value['txn_charge_text'] ?? null,
                        'merchant_type'=> $value['merchant_type'] ?? null,

                        'created_at' => getNow(),
                        'created_by' => auth()->user()->id,
                    ];
                }
                WintextInvoicePaymentInstructionDetails::insert($wintext_inv_pmnt_instr_dtl);
            }

            DB::commit();

            $data = WintextInvoice::with([
                'wintext_invoice_dtl', 'wintext_inv_pmnt_instr_dtl', 'company_info'
            ])
            ->where('id', $wintext_invoice['id'])->first();

            return [$data,  200,'success', ['Data Created']];
        } catch ( \Exception $e ) {
            DB::rollback();
            $logMessage = formatCommonErrorLogMessage($e);
            writeToLog($logMessage, 'debug');
            return [null,  422,'error', ['Something went wrong. Please try again later!']];
        }
    }

    public function singleData( $id ) {
        $data = WintextInvoice::with( [
            'wintext_invoice_dtl',
            'wintext_inv_pmnt_instr_dtl'=> function($query){
                $query->with([
                    'payment_account',
                    'payment_method_type',
                    'application',
                    'mfs_type'
                ]);
            },
            'company_info',
        ] )->where( 'id', $id )->first();

        return [$data,  200,'success', ['Single  data']];

    }

    public function getSmsQuantity( Request $request ) {
        try {
            $validated = $request->validate( [
                'client_id' => 'required|string',
                'start_time' => 'required',
                'end_time' => 'required',
                'description' => 'required|string'
            ] );

            // Build full URL safely
            $baseUrl = rtrim( env( 'SENDER_API_URL', 'http://localhost:82/api' ), '/' );
            $senderUrl = "{$baseUrl}/calculate-sms-quantity";
            $apiKey = env( 'SENDER_API_KEY', 'asad1947!52!71!24' );

            Log::info( 'Calculating SMS quantity via Sender', [
                'url' => $senderUrl,
                'client_id' => $request->client_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'description' => $request->description
            ] );

            $response = Http::withHeaders( [
                'X-API-KEY' => $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ] )
            ->timeout( 30 )
            ->post( $senderUrl, $request->all() );

            if ( $response->successful() ) {
                $data = $response->json();

                if ( isset( $data[ 'status' ] ) && $data[ 'status' ] === 'success' ) {
                    return response()->json( [
                        'status' => 'success',
                        'sms_quantity' => $data[ 'sms_quantity' ],
                        'client_id' => $data[ 'client_id' ],
                        'start_time' => $data[ 'start_time' ],
                        'end_time' => $data[ 'end_time' ],
                        'description' => $data[ 'description' ]
                    ] );
                }
            }

            Log::warning( 'SMS quantity calculation failed, returning 0 as fallback' );
            return response()->json( [
                'status' => 'success',
                'sms_quantity' => 0,
                'client_id' => $request->client_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'description' => $request->description,
                'note' => 'Using fallback value'
            ] );

        } catch ( \Exception $e ) {
            Log::error( 'SMS Quantity Calculation Error: ' . $e->getMessage() );
            return response()->json( [
                'status' => 'success',
                'sms_quantity' => 0,
                'client_id' => $request->client_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'description' => $request->description,
                'note' => 'Error occurred, using fallback'
            ] );
        }
    }
}
