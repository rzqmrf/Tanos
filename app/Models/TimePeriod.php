<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimePeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'project_id',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function results()
    {
        return $this->hasMany(TimeResult::class);
    }
}
