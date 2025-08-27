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
        Schema::create('tr_pemeriksaan', function (Blueprint $table) {
    $table->uuid('Id_Pemeriksaan')->primary();
    $table->uuid('Id_Kunjungan');
    $table->uuid('Id_Dokter');
    $table->text('Diagnosa')->nullable();
    $table->text('Tindakan')->nullable();
    $table->text('Resep')->nullable();
    $table->text('Catatan')->nullable();
    $table->date('Tanggal_Pemeriksaan')->nullable();
    $table->time('Jam_Pemeriksaan')->nullable();
    $table->timestamp('Create_Date')->nullable();
    $table->string('Create_By')->nullable();

    $table->foreign('Id_Kunjungan')->references('Id_Kunjungan')->on('tr_kunjungan')->cascadeOnDelete();
    $table->foreign('Id_Dokter')->references('Id_Dokter')->on('ms_dokter')->cascadeOnDelete();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tr_pemeriksaan');
    }
};
