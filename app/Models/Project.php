<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'month',
        'regional',
        'segment',
        'cost',
        'active',
    ];

    public function wbsElements(): HasMany
    {
        return $this->hasMany(WbsElement::class);
    }

    public function payrollPeriods(): HasMany
    {
        return $this->hasMany(PayrollPeriod::class);
    }
}
