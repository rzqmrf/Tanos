<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotaBilling extends Model
{
    protected $fillable = [
        'project_id',
        'nota_number',
        'amount',
        'tax_code',
        'status',
        'sap_doc_number',
        'posted_at',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
