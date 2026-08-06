<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotaBillingItem extends Model
{
    protected $fillable = [
        'nota_billing_id',
        'pranota_billing_id',
        'item_name',
        'dpp_amount',
        'management_fee_amount',
        'ppn_amount',
        'total_amount',
    ];

    public function notaBilling(): BelongsTo
    {
        return $this->belongsTo(NotaBilling::class);
    }

    public function pranotaBilling(): BelongsTo
    {
        return $this->belongsTo(PranotaBilling::class);
    }
}
