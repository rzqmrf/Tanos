<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaCompanyBankAccount extends Model
{
    use HasFactory;

    protected $table = 'fa_company_bank_accounts';

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder',
        'branch',
        'currency',
        'chart_of_account_id',
        'is_primary',
        'active',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'active' => 'boolean',
    ];

    public function chartOfAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }
}
