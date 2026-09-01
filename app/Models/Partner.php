<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'city',
        'identity_card',
        'is_vendor',
        'is_customer',
        'chief_name',
        'chief_position',
        'status_hold_dana',
        'auto_generate_faktur',
        'trading_partner',
        'partner_group',
        'phone_1',
        'phone_2',
        'ftp_link',
        'ftp_port',
        'ftp_user',
        'ftp_pass',
        'kode_mdm',
        'description',
        'pic_name',
        'pic_phone',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'payment_terms_days',
        'active',
    ];

    protected $casts = [
        'is_vendor' => 'boolean',
        'is_customer' => 'boolean',
        'status_hold_dana' => 'boolean',
        'auto_generate_faktur' => 'boolean',
        'active' => 'boolean',
        'payment_terms_days' => 'integer',
    ];

    public function partnerType(): BelongsTo
    {
        return $this->belongsTo(PartnerType::class);
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(PartnerBankAccount::class);
    }

    public function businessSegments(): HasMany
    {
        return $this->hasMany(PartnerBusinessSegment::class);
    }
}
