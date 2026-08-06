<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RabBudgetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'rab_budget_id',
        'coa_code',
        'fund_center',
        'cost_center',
        'profit_center',
        'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec',
        'total_amount'
    ];

    protected $casts = [
        'jan' => 'decimal:2',
        'feb' => 'decimal:2',
        'mar' => 'decimal:2',
        'apr' => 'decimal:2',
        'may' => 'decimal:2',
        'jun' => 'decimal:2',
        'jul' => 'decimal:2',
        'aug' => 'decimal:2',
        'sep' => 'decimal:2',
        'oct' => 'decimal:2',
        'nov' => 'decimal:2',
        'dec' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];

    public function rabBudget()
    {
        return $this->belongsTo(RabBudget::class);
    }
}
