<?php

namespace App\Models;

use App\Models\PaymentAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\WintextInvoicePaymentInstructionDetails;

class MFSType extends Model {
    use HasFactory;
    protected $table = 'mfs_type';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function payment_account() {
        return $this->HasMany( PaymentAccount::class, 'mfs_type_id', 'id' );
    }

    public function wintext_inv_pmnt_instr_dtl() {
        return $this->HasMany( WintextInvoicePaymentInstructionDetails::class, 'mfs_type_id', 'id' );
    }
}
