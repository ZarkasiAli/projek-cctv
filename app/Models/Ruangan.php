<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $fillable = [
        'nama',
        'kode_ruangan',
        'lokasi',
        'link_stream_cctv',
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'ruangan_id');
    }

    /**
     * Get active jadwal right now for this ruangan.
     */
    public function getJadwalAktifAttribute()
    {
        $now = now();
        $currentTime = $now->format('H:i:s');
        $hariIndonesia = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $currentHari = $hariIndonesia[$now->format('l')] ?? 'Senin';

        return $this->jadwal()
            ->with(['dosen', 'mataKuliah'])
            ->where('hari', $currentHari)
            ->where('jam_mulai', '<=', $currentTime)
            ->where('jam_selesai', '>=', $currentTime)
            ->first();
    }

    /**
     * Determine if room is in use right now.
     */
    public function getIsUsedAttribute(): bool
    {
        return $this->jadwal_aktif !== null;
    }
}

