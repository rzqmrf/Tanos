<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'parent_id',
        'description',
        'regional',
        'cost_center',
        'unit_type',
        'valid_from',
        'valid_to',
        'active',
        'sent_to_sap'
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date',
        'active' => 'boolean',
        'sent_to_sap' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Division::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Division::class, 'parent_id');
    }

    public function jobPositions()
    {
        return $this->hasMany(JobPosition::class);
    }
}
