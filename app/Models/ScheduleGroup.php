<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'work_start',
        'work_end',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function assignments()
    {
        return $this->hasMany(ScheduleAssignment::class);
    }
}
