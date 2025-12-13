<?php

namespace App\Models;

use App\Models\CompanyInfo;
use Illuminate\Database\Eloquent\Model;

class Application extends Model {
    protected $table = 'application';
    protected $guarded = [ 'id' ];
    public $timestamps = false;

    public function company_info() {
        return $this->hasMany( CompanyInfo::class, 'app_id', 'id' );
    }

    public function payment_account() {
        return $this->hasMany( Application::class, 'app_id', 'id' );
    }
}
