<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'ecn_name',
        'employee_id',
        'movement_type',
        'status',
        'from_position_id',
        'to_position_id',
        'from_project_id',
        'to_project_id',
        'reference_number',
        'effective_date',
        'valid_from',
        'valid_to',
        'sent_to_sap'
    ];

    protected $casts = [
        'effective_date' => 'date',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'sent_to_sap' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function fromPosition()
    {
        return $this->belongsTo(JobPosition::class, 'from_position_id');
    }

    public function toPosition()
    {
        return $this->belongsTo(JobPosition::class, 'to_position_id');
    }

    public function fromProject()
    {
        return $this->belongsTo(Project::class, 'from_project_id');
    }

    public function toProject()
    {
        return $this->belongsTo(Project::class, 'to_project_id');
    }
}
