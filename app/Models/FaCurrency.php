<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FaCurrency extends Model
{
    use HasFactory;

    protected $table = 'fa_currencies';

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'is_default',
        'active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'active' => 'boolean',
    ];

    public function rates(): HasMany
    {
        return $this->hasMany(FaCurrencyRate::class, 'currency_id');
    }

    public function latestRate(): HasOne
    {
        return $this->hasOne(FaCurrencyRate::class, 'currency_id')->latestOfMany('effective_date');
    }
}
