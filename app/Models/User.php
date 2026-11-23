<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'google_id',
    ];

    public function isSuperAdmin(): bool
    {
        $role = strtolower(trim((string) $this->role));
        return in_array($role, ['superadmin', 'super admin', 'super_admin']);
    }

    public function isAdmin(): bool
    {
        $role = strtolower(trim((string) $this->role));
        return in_array($role, ['admin', 'superadmin', 'super admin', 'super_admin']);
    }

    public function isManajemen(): bool
    {
        return strtolower(trim((string) $this->role)) === 'manajemen';
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function sesiDiverifikasi()
    {
        return $this->hasMany(SesiKelas::class, 'diverifikasi_oleh');
    }
}
