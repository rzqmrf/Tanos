<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialEquipment extends Model
{
    use HasFactory;

    protected $table = 'materials_equipment';

    protected $fillable = [
        'equipment_code',
        'name',
        'category',
        'brand_model',
        'serial_number',
        'project_id',
        'condition',
        'purchase_date',
        'purchase_cost',
        'last_service_date',
        'next_service_date',
        'certification_expiry',
        'notes',
        'active',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'last_service_date' => 'date',
        'next_service_date' => 'date',
        'certification_expiry' => 'date',
        'purchase_cost' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
