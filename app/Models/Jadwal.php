<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'ruangan_id',
        'dosen_id',
        'mata_kuliah_id',
        'hari',
        'jam_ke_mulai',
        'jam_ke_selesai',
        'jam_mulai',
        'jam_selesai',
        'prodi',
        'kelas',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function dosen()
    {
        return $this->belongsTo(MasterDosen::class, 'dosen_id');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MasterMataKuliah::class, 'mata_kuliah_id');
    }

    public function sesiKelas()
    {
        return $this->hasMany(SesiKelas::class, 'jadwal_id');
    }

    /**
     * Get formatted jam range e.g. 08:00 - 10:00
     */
    public function getJamRangeAttribute(): string
    {
        $mulai = substr($this->jam_mulai, 0, 5);
        $selesai = substr($this->jam_selesai, 0, 5);
        return "{$mulai} - {$selesai}";
    }

    /**
     * Get formatted jam ke range e.g. Jam ke 1 - 2
     */
    public function getJamKeRangeAttribute(): string
    {
        if ($this->jam_ke_mulai && $this->jam_ke_selesai) {
            return "Jam ke-{$this->jam_ke_mulai} s/d {$this->jam_ke_selesai}";
        }
        return '-';
    }
}

