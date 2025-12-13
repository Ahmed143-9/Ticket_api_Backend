<?php

namespace App\Models;

use App\Models\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class  CompanyInfo extends Model {
    use HasFactory;
    protected $table = 'company_info';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function application()
    {
        return $this->belongsTo(Application::class,  'app_id', 'id');
    }

}
