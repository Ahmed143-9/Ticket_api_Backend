<?php
// app/Models/FirstFaceAssignment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirstFaceAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department',
        'is_active',
        'assigned_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'assigned_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedByUser()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDepartment($query, $department)
    {
        if ($department === 'all' || $department === null) {
            return $query->whereNull('department');
        }
        return $query->where('department', $department);
    }
}