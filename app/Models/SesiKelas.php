<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiKelas extends Model
{
    use HasFactory;

    protected $table = 'sesi_kelas';

    protected $fillable = [
        'jadwal_id',
        'tanggal',
        'status_verifikasi',
        'diverifikasi_oleh',
        'diverifikasi_pada',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'diverifikasi_pada' => 'datetime',
        ];
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}

