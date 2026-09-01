<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectMasterData extends Model
{
    use HasFactory;

    protected $table = 'project_master_data';

    protected $fillable = [
        'category',
        'code',
        'name',
        'uom',
        'scope',
        'project_type',
        'seq',
        'coa',
        'description',
        'validity_start',
        'validity_end',
    ];
}
