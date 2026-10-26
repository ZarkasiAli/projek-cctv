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
 Schema::create('ruangan', function (Blueprint $table) {
    $table->increments('id');
    $table->string('nama', 50);
    $table->string('kode_ruangan', 20)->nullable();
    $table->string('lokasi', 100)->nullable();
    $table->string('link_stream_cctv', 255)->nullable();
    $table->string('status_cctv', 20)->default('Online');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangan');
    }
};