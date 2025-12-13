<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  WintextSupportData extends Model {
    use HasFactory;
    protected $table = 'wintext_support_data';
    protected $primaryKey = 'id';
    protected $guarded = [];


}
