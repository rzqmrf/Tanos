<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'schedule_group_id',
        'valid_from',
        'valid_to'
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scheduleGroup()
    {
        return $this->belongsTo(ScheduleGroup::class);
    }
}
