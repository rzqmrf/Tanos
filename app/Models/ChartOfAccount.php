<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_group_id',
        'parent_id',
        'code',
        'name',
        'level',
        'normal_balance',
        'is_header',
        'description',
        'active',
    ];

    protected $casts = [
        'level' => 'integer',
        'is_header' => 'boolean',
        'active' => 'boolean',
    ];

    public function accountGroup()
    {
        return $this->belongsTo(FaAccountGroup::class, 'account_group_id');
    }

    public function parent()
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }
}
