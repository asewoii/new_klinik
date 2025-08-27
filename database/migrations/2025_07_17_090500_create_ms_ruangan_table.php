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
        Schema::create('ms_ruangan', function (Blueprint $table) {
            $table->uuid('Id_Ruangan')->primary();
            $table->string('Nama_Ruangan');
            $table->string('Jenis_Ruangan');
            $table->string('Lantai');
            $table->boolean('Status')->default(true);
            $table->text('Keterangan')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_ruangan');
    }
};
