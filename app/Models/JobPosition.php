<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'division_id',
        'parent_id',
        'code',
        'name',
        'regional',
        'is_leader',
        'cost_center',
        'cost_center_name',
        'valid_from',
        'valid_to',
        'active',
        'no_contract',
        'non_formation',
        'sent_to_sap'
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date',
        'is_leader' => 'boolean',
        'active' => 'boolean',
        'no_contract' => 'boolean',
        'non_formation' => 'boolean',
        'sent_to_sap' => 'boolean',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function parent()
    {
        return $this->belongsTo(JobPosition::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(JobPosition::class, 'parent_id');
    }

    public function movements()
    {
        return $this->hasMany(EmployeeMovement::class, 'to_position_id');
    }
}
