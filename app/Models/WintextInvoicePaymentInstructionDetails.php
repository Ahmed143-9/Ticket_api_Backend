<?php

namespace App\Models;

use App\Models\User;
use App\Models\MFSType;
use App\Models\Application;
use App\Models\PaymentAccount;
use App\Models\WintextInvoice;
use App\Models\PaymentMethodType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class  WintextInvoicePaymentInstructionDetails extends Model {
    use HasFactory;
    protected $table = 'wintext_inv_pmnt_instr_dtl';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function wintext_invoice() {
        return $this->belongsTo( WintextInvoice::class, 'wintext_invoice_id', 'id' );
    }

    public function payment_account() {
        return $this->belongsTo( PaymentAccount::class, 'payment_account_id', 'id' );
    }

    public function payment_method_type() {
        return $this->belongsTo( PaymentMethodType::class, 'payment_method_type_id', 'id' );
    }

    public function application() {
        return $this->belongsTo( Application::class, 'app_id', 'id' );
    }

    public function mfs_type() {
        return $this->belongsTo( MFSType::class, 'mfs_type_id', 'id' );
    }

    public function creator() {
        return $this->belongsTo( User::class,  'created_by', 'id' );
    }


}

