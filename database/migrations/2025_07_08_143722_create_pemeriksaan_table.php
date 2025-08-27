<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('pemeriksaan', function (Blueprint $table) {
        $table->string('Id_Pemeriksaan', 225)->primary();
        $table->string('Id_Kunjungan', 225);
        $table->string('Id_Dokter', 225);
        $table->text('Diagnosa')->nullable();
        $table->text('Tindakan')->nullable();
        $table->text('Resep')->nullable();
        $table->text('Catatan')->nullable();
        $table->date('Tanggal_Pemeriksaan')->nullable();
        $table->time('Jam_Pemeriksaan')->nullable();
        $table->timestamp('Create_Date')->useCurrent();
        $table->string('Create_By', 100)->nullable();

        // Foreign Key
        $table->foreign('Id_Kunjungan')->references('Id_Kunjungan')->on('kunjungan')->onDelete('cascade');
        $table->foreign('Id_Dokter')->references('Id_Dokter')->on('ms_dokter')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan');
    }
};
