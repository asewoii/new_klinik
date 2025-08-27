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
        Schema::create('ms_sesi', function (Blueprint $table) {
    $table->uuid('Id_Sesi')->primary();
    $table->string('Nama_Sesi');
    $table->time('Mulai_Sesi');
    $table->time('Selesai_Sesi');
    $table->boolean('Status')->default(true);
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
        Schema::dropIfExists('ms_sesi');
    }
};
