<?php

namespace App\Models;

use App\Models\Bank;
use App\Models\MFSType;
use App\Models\Application;
use App\Models\PaymentMethodType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class  PaymentAccount extends Model {
    use HasFactory;
    protected $table = 'payment_account';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function payment_method_type() {
        return $this->belongsTo( PaymentMethodType::class, 'payment_method_type_id', 'id' );
    }

    public function mfs_type() {
        return $this->belongsTo( MFSType::class, 'mfs_type_id', 'id' );
    }

    public function pmnt_rcv_bank() {
        return $this->belongsTo( Bank::class, 'pmnt_rcv_bank_id', 'id' );
    }

    public function application() {
        return $this->belongsTo( Application::class, 'app_id', 'id' );
    }
}
