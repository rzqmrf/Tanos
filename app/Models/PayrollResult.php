<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollResult extends Model
{
    protected $fillable = [
        'payroll_period_id',
        'employee_id',
        'days_present',
        'overtime_hours',
        'basic_salary',
        'transport_allowance',
        'allowances',
        'overtime_pay',
        'deductions',
        'net_salary',
        'sap_doc_number',
        'posted_at',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class, 'payroll_period_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
