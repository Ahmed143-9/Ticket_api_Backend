<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LogType extends Model
{
    protected $table = 'log_type';
    protected $guarded = ['id'];
}
