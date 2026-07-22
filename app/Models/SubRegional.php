<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubRegional extends Model
{
    use HasFactory;

    protected $fillable = [
        'regional_id',
        'name',
    ];

    public function regional(): BelongsTo
    {
        return $this->belongsTo(Regional::class);
    }
}
