<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CicoCorrection extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'reason',
        'attachment',
        'status',
        'approved_by',
        'approval_date',
    ];

    protected $casts = [
        'date' => 'date',
        'approval_date' => 'datetime',
    ];

    /**
     * Relationship to Employee.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Relationship to User (who approved).
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
