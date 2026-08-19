<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeoInitial extends Model
{
    use HasFactory;

    protected $fillable = [
        'peo_setting_id',
        'no',
        'jenis_pihak',
        'kode_jabatan',
        'nama_jabatan',
        'nama_pegawai',
        'priority',
    ];

    public function peoSetting()
    {
        return $this->belongsTo(PeoSetting::class);
    }
}
