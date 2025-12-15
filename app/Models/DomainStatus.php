<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainStatus extends Model
{
    use HasFactory;

    protected $fillable = ['domain', 'is_up', 'last_checked_at'];
      // Add this to ensure dates are cast to Carbon instances
      protected $dates = ['created_at', 'updated_at'];
}