<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            if (! Schema::hasColumn('jadwal', 'jam_ke_mulai')) {
                $table->unsignedInteger('jam_ke_mulai')->nullable()->after('hari');
            }
            if (! Schema::hasColumn('jadwal', 'jam_ke_selesai')) {
                $table->unsignedInteger('jam_ke_selesai')->nullable()->after('jam_ke_mulai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropColumn(['jam_ke_mulai', 'jam_ke_selesai']);
        });
    }
};
