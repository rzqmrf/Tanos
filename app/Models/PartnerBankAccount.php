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
        'valid_from',
        'valid_to',
        'document_status',
        'h2h_response_code',
        'h2h_response_message',
        'attachment_file',
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
