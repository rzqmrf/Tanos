<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaAccountGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function accounts()
    {
        return $this->hasMany(ChartOfAccount::class, 'account_group_id');
    }
}
