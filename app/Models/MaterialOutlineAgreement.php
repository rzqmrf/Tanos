<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialOutlineAgreement extends Model
{
    use HasFactory;

    protected $table = 'materials_outline_agreements';

    protected $fillable = [
        'agreement_number',
        'partner_id',
        'title',
        'agreement_type',
        'target_value',
        'currency',
        'start_date',
        'end_date',
        'status',
        'terms',
        'notes',
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
}
