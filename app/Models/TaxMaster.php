<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'rate_percent',
        'tax_type',
        'description',
        'active',
    ];

    protected $casts = [
        'rate_percent' => 'float',
        'active' => 'boolean',
    ];
}
