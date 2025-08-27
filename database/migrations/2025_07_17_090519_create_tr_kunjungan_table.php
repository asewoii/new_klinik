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
    Schema::create('tr_kunjungan', function (Blueprint $table) {
    $table->uuid('Id_Kunjungan')->primary();
    $table->uuid('Id_Dokter')->nullable();
    $table->string('Jadwal')->nullable();
    $table->uuid('Id_Ruangan')->nullable();
    $table->string('QRK');
    $table->uuid('Id_Pasien');
    $table->uuid('Id_Layanan');
    $table->string('Keluhan');
    $table->string('Nik');
    $table->string('Nama_Pasien');
    $table->date('Tanggal_Registrasi');
    $table->date('Jadwal_Kedatangan');
    $table->integer('Nomor_Urut');
    $table->string('Status')->default('Menunggu');
    $table->timestamp('Create_Date')->nullable();
    $table->string('Create_By')->nullable();

    $table->foreign('Id_Dokter')->references('Id_Dokter')->on('ms_dokter')->nullOnDelete();
    $table->foreign('Id_Pasien')->references('Id_Pasien')->on('ms_pasien')->cascadeOnDelete();
    $table->foreign('Id_Layanan')->references('Id_Layanan')->on('ms_layanan')->cascadeOnDelete();
    $table->foreign('Id_Ruangan')->references('Id_Ruangan')->on('ms_ruangan')->nullOnDelete();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_kunjungan');
    }
};
