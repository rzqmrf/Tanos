<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'gender',
        'priority_level',
        'deduction_absent',
        'valid_from',
        'valid_to',
        'active'
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date',
        'active' => 'boolean'
    ];
}
