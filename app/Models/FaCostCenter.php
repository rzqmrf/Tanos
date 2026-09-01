<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaCostCenter extends Model
{
    use HasFactory;

    protected $table = 'fa_cost_centers';

    protected $fillable = [
        'profit_center_id',
        'code',
        'name',
        'department',
        'person_in_charge',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function profitCenter(): BelongsTo
    {
        return $this->belongsTo(FaProfitCenter::class, 'profit_center_id');
    }
}
