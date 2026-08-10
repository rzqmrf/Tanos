<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'time_period_id',
        'employee_id',
        'workdays',
        'present_days',
        'absent_days',
        'late_days',
        'early_departure_days',
        'leave_days',
        'overtime_hours',
        'deduction_amount'
    ];

    protected $casts = [
        'overtime_hours' => 'decimal:2',
        'deduction_amount' => 'decimal:2'
    ];

    public function timePeriod()
    {
        return $this->belongsTo(TimePeriod::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
