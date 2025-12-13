<?php

namespace App\Http\Requests\Modules\WintextInvoice;

use App\Rules\PhoneRule;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class WintextInvoiceUpdateRequest extends FormRequest
{
    use ApiResponser;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|numeric|exists:wintext_invoice,id',
            'client_id' => 'required|string',
            'invoice_number' => 'required|string|max:100|unique:wintext_invoice,invoice_number,' . $this->request->get('id'),
            'kam' => 'nullable|string|max:255',
            'prepared_by' => 'nullable|string|max:255',
            'received_by' => 'nullable|string|max:255',
            'company_id' => 'nullable|integer',
            'billing_date' => 'required|date',
            'client_name' => 'required|string|max:255',
            'client_address' => 'nullable|string',
            'billing_attention' => 'nullable|string',
            'billing_attention_phone' => ['nullable', new PhoneRule],
            'subtotal' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'payment_instructions' => 'nullable|string',
            'amount_in_words' => 'required|string',
            'billing_start_date' => 'nullable|date',
            'billing_end_date' => 'nullable|date',
            'contract_no' => 'nullable|string',
            'vat' => 'nullable|numeric',
            'mushak_file_path' => 'nullable|string',
            'bin' => 'nullable|string',
            'section' => 'nullable|string',
            'client_email' => 'nullable|string',

            'wintext_invoice_dtl' => 'required|array|min:1',
            'wintext_invoice_dtl.*.description' => 'required|string|max:255',
            'wintext_invoice_dtl.*.sms_qty' => 'required|numeric|min:0',
            'wintext_invoice_dtl.*.unit_price' => 'required|numeric|min:0',
            'wintext_invoice_dtl.*.total' => 'required|numeric|min:0',
            'wintext_invoice_dtl.*.start_time' => 'nullable|date',
            'wintext_invoice_dtl.*.end_time' => 'nullable|date',


            'wintext_inv_pmnt_instr_dtl' => 'required|array|min:1',
            'wintext_inv_pmnt_instr_dtl.*.payment_account_id' => 'required|integer|exists:payment_account,id',
            'wintext_inv_pmnt_instr_dtl.*.payment_method_type_id' => 'required|integer|exists:payment_method_type,id',
            'wintext_inv_pmnt_instr_dtl.*.receiver_name' => 'nullable|string|max:255',
            'wintext_inv_pmnt_instr_dtl.*.pmnt_rcv_bank' => 'nullable|string|max:255',
            'wintext_inv_pmnt_instr_dtl.*.pmnt_receive_acc' => 'nullable|string|max:255',
            'wintext_inv_pmnt_instr_dtl.*.pmnt_rcv_branch' => 'nullable|string|max:255',
            'wintext_inv_pmnt_instr_dtl.*.pmnt_rcv_rn' => 'nullable|string|max:255',
            'wintext_inv_pmnt_instr_dtl.*.mfs_type_id' => 'nullable|integer|exists:mfs_type,id',
            'wintext_inv_pmnt_instr_dtl.*.mfs_type_id' => 'nullable|integer|exists:mfs_type,id',
            'wintext_inv_pmnt_instr_dtl.*.note' => 'nullable|string',
            'wintext_inv_pmnt_instr_dtl.*.txn_charge' => 'nullable|numeric',
            'wintext_inv_pmnt_instr_dtl.*.txn_charge_text' => 'nullable|string',
            'wintext_inv_pmnt_instr_dtl.*.merchant_type' => 'nullable|string',

        ];
    }
    public function messages()
    {
        $messages = [
        ];

        return $messages;
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->set_response(null, 422, 'error', array_slice($validator->errors()->all(), 0, 2),
                new Request (array_merge( $this->request->all() ))
            )
        );
    }
}
