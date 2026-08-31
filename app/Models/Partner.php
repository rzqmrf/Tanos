<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_type_id',
        'code',
        'name',
        'npwp',
        'email',
        'phone',
        'address',
        'pic_name',
        'pic_phone',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'payment_terms_days',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'payment_terms_days' => 'integer',
    ];

    public function partnerType()
    {
        return $this->belongsTo(PartnerType::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(PartnerBankAccount::class);
    }
}
