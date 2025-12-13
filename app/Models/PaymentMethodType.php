<?php

namespace App\Models;

use App\Models\PaymentMethodType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class  PaymentMethodType extends Model {
    use HasFactory;
    protected $table = 'payment_method_type';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function payment_account() {
        return $this->hasMany( PaymentMethodType::class, 'payment_method_type_id', 'id' );
    }
}
