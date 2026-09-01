<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FaProfitCenter extends Model
{
    use HasFactory;

    protected $table = 'fa_profit_centers';

    protected $fillable = [
        'code',
        'name',
        'segment',
        'person_in_charge',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function costCenters(): HasMany
    {
        return $this->hasMany(FaCostCenter::class, 'profit_center_id');
    }
}
