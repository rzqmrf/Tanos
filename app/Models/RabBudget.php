<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RabBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'document_number',
        'name',
        'year',
        'total_revenue',
        'total_cost',
        'sap_status',
        'doc_status'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function items()
    {
        return $this->hasMany(RabBudgetItem::class);
    }
}
