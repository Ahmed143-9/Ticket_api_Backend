<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class  WintextInvoiceDetail extends Model {
    use HasFactory;
    protected $table = 'wintext_invoice_dtl';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function creator() {
        return $this->belongsTo( User::class,  'created_by', 'id' );
    }

}
