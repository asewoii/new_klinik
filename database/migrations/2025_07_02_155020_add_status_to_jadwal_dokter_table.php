<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_dokter', function (Blueprint $table) {
            $table->string('Status')->default('Open');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_dokter', function (Blueprint $table) {
            $table->dropColumn('Status');
        });
    }
};
