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
        // 1. Update master_dosen: add kode_dosen and make nip nullable
        Schema::table('master_dosen', function (Blueprint $table) {
            if (! Schema::hasColumn('master_dosen', 'kode_dosen')) {
                $table->string('kode_dosen', 20)->nullable()->after('id');
            }
            $table->string('nip', 30)->nullable()->change();
        });

        // 2. Update ruangan: make lokasi nullable to accommodate rooms without specific floor/location
        Schema::table('ruangan', function (Blueprint $table) {
            $table->string('lokasi', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruangan', function (Blueprint $table) {
            $table->string('lokasi', 100)->nullable(false)->change();
        });

        Schema::table('master_dosen', function (Blueprint $table) {
            $table->string('nip', 30)->nullable(false)->change();
            if (Schema::hasColumn('master_dosen', 'kode_dosen')) {
                $table->dropColumn('kode_dosen');
            }
        });
    }
};
