<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        $user1 = User::find(1);
        if ($user1) {
            $user1->role = 'superadmin';
            $user1->nama = 'Super Admin';
            $user1->save();
        }

        if (!User::where('email', 'superadmin@monitoring.com')->exists()) {
            User::create([
                'nama' => 'Super Admin',
                'email' => 'superadmin@monitoring.com',
                'password' => Hash::make('admin123'),
                'role' => 'superadmin',
            ]);
        }

        if (!User::where('role', 'admin')->exists()) {
            User::create([
                'nama' => 'Admin Operasional',
                'email' => 'operator@monitoring.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]);
        }
    }

    public function down(): void
    {
        //
    }
};
