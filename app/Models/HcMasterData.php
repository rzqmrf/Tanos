<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HcMasterData extends Model
{
    protected $table = 'hc_master_data';

    protected $fillable = [
        'category',
        'code',
        'name',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
