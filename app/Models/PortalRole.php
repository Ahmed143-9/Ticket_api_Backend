<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  PortalRole extends Model {
    use HasFactory;
    protected $table = 'portal_role';
    protected $primaryKey = 'id';
    protected $guarded = [];

}
