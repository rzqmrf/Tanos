<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PranotaBillingItem extends Model
{
    protected $fillable = [
        'pranota_billing_id',
        'wbs_element_id',
        'item_name',
        'dpp_amount',
        'management_fee_rate',
        'management_fee_amount',
        'ppn_rate',
        'ppn_amount',
        'total_amount',
    ];

    public function pranotaBilling(): BelongsTo
    {
        return $this->belongsTo(PranotaBilling::class);
    }

    public function wbsElement(): BelongsTo
    {
        return $this->belongsTo(WbsElement::class);
    }
}
