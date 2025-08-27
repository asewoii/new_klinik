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
    Schema::create('ms_layanan', function (Blueprint $table) {
    $table->uuid('Id_Layanan')->primary();
    $table->string('Nama_Layanan');
    $table->timestamp('Create_Date')->nullable();
    $table->string('Create_By')->nullable();
    $table->timestamp('Last_Update')->nullable();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ms_layanan');
    }
};
