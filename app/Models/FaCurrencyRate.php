<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaCurrencyRate extends Model
{
    use HasFactory;

    protected $table = 'fa_currency_rates';

    protected $fillable = [
        'currency_id',
        'rate_to_idr',
        'effective_date',
        'source',
        'notes',
    ];

    protected $casts = [
        'rate_to_idr' => 'decimal:4',
        'effective_date' => 'date',
    ];

    public function currency(): BelongsTo
    {
        return $this->belongsTo(FaCurrency::class, 'currency_id');
    }
}
