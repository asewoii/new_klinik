<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuanganTable extends Migration
{
    public function up()
    {
        Schema::create('ruangan', function (Blueprint $table) {
            $table->uuid('Id_Ruangan')->primary(); // UUID sebagai primary key

            $table->string('Nama_Ruangan', 100);
            $table->string('Jenis_Ruangan', 50);
            $table->integer('Lantai');
            $table->enum('Status', ['aktif', 'nonaktif', 'dalam perbaikan'])->default('aktif');
            $table->text('Keterangan')->nullable();

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('ruangan');
    }
}
