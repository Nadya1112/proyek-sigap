<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFasumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('fasums', function (Blueprint $table) {
        $table->id();
        $table->string('nama_perumahan');
        $table->string('alamat')->nullable();
        $table->enum('status_sertifikat',['sudah','belum'])->default('belum');
        $table->date('tanggal_serah')->nullable();
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fasums');
    }
}
