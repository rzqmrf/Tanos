<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'valid_from',
        'valid_to',
        'late_tolerance_minutes',
        'early_departure_minutes',
        'is_active'
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date',
        'is_active' => 'boolean'
    ];
}
