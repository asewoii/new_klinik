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
        Schema::create('ms_pasien', function (Blueprint $table) {
            $table->uuid('Id_Pasien')->primary();
            $table->string('Qr_Url')->nullable();
            $table->string('Nik')->unique();
            $table->string('Nama_Pasien');
            $table->date('Tanggal_Lahir');
            $table->integer('Umur');
            $table->enum('Jk', ['L', 'P']);
            $table->text('Alamat');
            $table->string('No_Tlp');
            $table->string('Pin');
            $table->date('Tanggal_Registrasi');
            $table->string('Dibuat_Oleh')->nullable();
            $table->timestamp('Create_Date')->nullable();
            $table->string('Create_By')->nullable();
            $table->timestamp('Last_Update')->nullable();
            $table->string('Last_Update_By')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_pasien');
    }
};
