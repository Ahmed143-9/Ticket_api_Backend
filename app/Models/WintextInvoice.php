<?php

namespace App\Models;

use App\Models\User;
use App\Models\CompanyInfo;
use App\Models\WintextInvoiceDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\WintextInvoicePaymentInstructionDetails;

class  WintextInvoice extends Model {
    use HasFactory;
    protected $table = 'wintext_invoice';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function wintext_invoice_dtl() {
        return $this->HasMany( WintextInvoiceDetail::class, 'wintext_invoice_id', 'id' );
    }

    public function wintext_inv_pmnt_instr_dtl() {
        return $this->HasMany( WintextInvoicePaymentInstructionDetails::class, 'wintext_invoice_id', 'id' );
    }

    public function company_info() {
        return $this->belongsTo( CompanyInfo::class, 'company_id', 'id' );
    }

    public function creator() {
        return $this->belongsTo( User::class,  'created_by', 'id' );
    }

    public function modifier() {
        return $this->belongsTo( User::class,  'updated_by', 'id' );

    }
}
