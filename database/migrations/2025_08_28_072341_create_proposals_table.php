<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('proposals', function (Blueprint $table) {
        $table->id();
        $table->string('nama_pengaju');
        $table->string('kontak');
        $table->string('nama_perumahan')->nullable();
        $table->string('alamat')->nullable();
        $table->string('file_path');
        $table->enum('status',['terkirim','diproses','disetujui','ditolak'])->default('terkirim');
        $table->text('catatan')->nullable();
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
        Schema::dropIfExists('proposals');
    }
}
