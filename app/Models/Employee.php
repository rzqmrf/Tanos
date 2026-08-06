<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'role',
        'month',
        'regional',
        'sub_regional',
        'segment',
        'nipp',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'ptkp_status',
        'tmt_date',
        'bpjs_kesehatan_number',
        'bpjs_ketenagakerjaan_number',
        'project_id',
        'job_position_id'
    ];

    protected $casts = [
        'tmt_date' => 'date',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function cicoCorrections()
    {
        return $this->hasMany(CicoCorrection::class);
    }

    public function movements()
    {
        return $this->hasMany(EmployeeMovement::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function jobPosition()
    {
        return $this->belongsTo(JobPosition::class);
    }
}
