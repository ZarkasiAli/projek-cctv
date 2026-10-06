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
        Schema::create('master_mata_kuliah', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_mata_kuliah', 20);
            $table->string('nama_mata_kuliah', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_mata_kuliah');
    }
};