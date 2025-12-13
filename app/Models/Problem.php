<?php
// app/Models/Problem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Problem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'statement',
        'department',
        'priority',
        'description',
        'assigned_to',
        'created_by',
        'status',
        'assignment_history',
        'images' // <--- added
    ];

    protected $casts = [
        'assignment_history' => 'array',
        'images' => 'array', // <--- added
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'asc');
    }
}
