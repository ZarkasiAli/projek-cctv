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
        Schema::create('jadwal', function (Blueprint $table) {
    $table->increments('id');
    $table->unsignedInteger('ruangan_id');
    $table->string('hari', 20);
    $table->time('jam_mulai');
    $table->time('jam_selesai');
    $table->string('mata_kuliah', 100);
    $table->string('dosen', 100);
    $table->string('prodi', 50);
    $table->string('kelas', 50);

    $table->foreign('ruangan_id')
          ->references('id')
          ->on('ruangan');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};