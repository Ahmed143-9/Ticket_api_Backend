<?php

namespace App\Models;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class  Bank extends Model {
    use HasFactory;
    protected $table = 'bank';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function payment_account() {
        return $this->hasMany( Bank::class, 'pmnt_rcv_bank_id', 'id' );
    }

}
