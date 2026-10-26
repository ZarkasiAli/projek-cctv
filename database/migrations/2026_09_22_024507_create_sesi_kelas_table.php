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
        Schema::create('sesi_kelas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('jadwal_id');
            $table->date('tanggal');
            $table->string('status_verifikasi', 30)->default('belum');
            $table->text('catatan')->nullable();
            $table->unsignedInteger('diverifikasi_oleh')->nullable();
            $table->timestamp('diverifikasi_pada')->nullable();

            $table->foreign('jadwal_id')
                  ->references('id')
                  ->on('jadwal')
                  ->onDelete('cascade');

            $table->foreign('diverifikasi_oleh')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_kelas');
    }
};