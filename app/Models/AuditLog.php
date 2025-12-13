<?php

namespace App\Models;

use App\Models\User;
use App\Models\LogType;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_log';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function logtype()
    {
        return $this->belongsTo(LogType::class, 'log_type_id', 'id');
    }

}
