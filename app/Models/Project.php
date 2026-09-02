<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'project_code',
        'id_project_humanis',
        'project_name',
        'description',
        'customer_name',
        'vendor',
        'contract_number',
        'project_category',
        'contract_type',
        'location',
        'regional_unit',
        'unit_kerja',
        'start_date',
        'end_date',
        'validity_start',
        'validity_end',
        'cost_center',
        'fund_center',
        'month',
        'regional',
        'segment',
        'cost',
        'active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'validity_start' => 'date',
        'validity_end' => 'date',
        'active' => 'boolean',
    ];

    public function wbsElements(): HasMany
    {
        return $this->hasMany(WbsElement::class);
    }

    public function payrollPeriods(): HasMany
    {
        return $this->hasMany(PayrollPeriod::class);
    }

    public function rabBudget()
    {
        return $this->hasOne(RabBudget::class);
    }

    public function pranotaBillings(): HasMany
    {
        return $this->hasMany(PranotaBilling::class);
    }

    public function notaBillings(): HasMany
    {
        return $this->hasMany(NotaBilling::class);
    }
}
