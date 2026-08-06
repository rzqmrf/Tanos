<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WbsElement extends Model
{
    protected $fillable = [
        'project_id',
        'parent_id',
        'wbs_code',
        'wbs_name',
        'wbs_category',
        'weight',
        'expected_start',
        'expected_end',
        'sent_to_sap',
    ];

    protected $casts = [
        'expected_start' => 'date',
        'expected_end' => 'date',
        'sent_to_sap' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(WbsElement::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(WbsElement::class, 'parent_id');
    }

    public function payrollComponents(): HasMany
    {
        return $this->hasMany(PayrollComponent::class, 'wbs_element_id');
    }
}
