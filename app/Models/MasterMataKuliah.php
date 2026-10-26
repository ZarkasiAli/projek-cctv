<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMataKuliah extends Model
{
    use HasFactory;

    protected $table = 'master_mata_kuliah';

    protected $fillable = [
        'kode_mata_kuliah',
        'nama_mata_kuliah',
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'mata_kuliah_id');
    }
}
