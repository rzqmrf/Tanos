<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosition extends Model
{
    protected $fillable = [
        'division_id',
        'code',
        'name',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function movements()
    {
        return $this->hasMany(EmployeeMovement::class, 'to_position_id');
    }
}
