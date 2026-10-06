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
            $table->unsignedInteger('dosen_id')->after('ruangan_id');
            $table->unsignedInteger('mata_kuliah_id')->after('dosen_id');
            $table->unsignedInteger('jam_ke')->after('hari');
        });

        Schema::table('jadwal', function (Blueprint $table) {
            $table->foreign('dosen_id')
                  ->references('id')
                  ->on('master_dosen');

            $table->foreign('mata_kuliah_id')
                  ->references('id')
                  ->on('master_mata_kuliah');

            $table->dropColumn(['dosen', 'mata_kuliah']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropForeign(['dosen_id']);
            $table->dropForeign(['mata_kuliah_id']);

            $table->string('dosen', 100);
            $table->string('mata_kuliah', 100);

            $table->dropColumn([
                'dosen_id',
                'mata_kuliah_id',
                'jam_ke',
            ]);
        });
    }
};