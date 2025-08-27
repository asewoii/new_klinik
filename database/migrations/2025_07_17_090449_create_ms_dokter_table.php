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
        Schema::create('ms_dokter', function (Blueprint $table) {
            $table->uuid('Id_Dokter')->primary();
            $table->string('Nama_Dokter');
            $table->string('Spesialis');
            $table->string('No_Telp');
            $table->string('Email');
            $table->text('Alamat');
            $table->json('Jadwal_Dokter');
            $table->timestamp('Create_Date')->nullable();
            $table->string('Create_By')->nullable();
            $table->timestamp('Last_Update')->nullable();
            $table->string('Last_Update_By')->nullable();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_dokter');
    }
};
