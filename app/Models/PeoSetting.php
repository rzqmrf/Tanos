<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type',
        'customer',
        'project_name',
        'project_code',
        'tab_category',
    ];

    public function signers()
    {
        return $this->hasMany(PeoSigner::class)->orderBy('no');
    }

    public function initials()
    {
        return $this->hasMany(PeoInitial::class)->orderBy('no');
    }
}
