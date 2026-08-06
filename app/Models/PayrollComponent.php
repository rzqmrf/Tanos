<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollComponent extends Model
{
    protected $fillable = [
        'payroll_period_id',
        'wbs_element_id',
        'code',
        'name',
        'type',
        'amount',
        'formula_expression',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class, 'payroll_period_id');
    }

    public function wbsElement(): BelongsTo
    {
        return $this->belongsTo(WbsElement::class, 'wbs_element_id');
    }
}
