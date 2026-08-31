<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerBankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_id',
        'bank_name',
        'account_number',
        'account_holder',
        'branch',
        'is_primary',
        'active',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'active' => 'boolean',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
