<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaFundCenter extends Model
{
    use HasFactory;

    protected $table = 'fa_fund_centers';

    protected $fillable = [
        'code',
        'name',
        'budget_limit',
        'currency',
        'description',
        'active',
    ];

    protected $casts = [
        'budget_limit' => 'decimal:2',
        'active' => 'boolean',
    ];
}
