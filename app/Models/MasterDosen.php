<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterDosen extends Model
{
    use HasFactory;

    protected $table = 'master_dosen';

    protected $fillable = [
        'kode_dosen',
        'nip',
        'nama',
        'no_hp',
        'status_aktif',
    ];

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
        ];
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'dosen_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status_aktif', 1);
    }
}
