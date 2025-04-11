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
        Schema::create('lowongan', function (Blueprint $table) {
            $table->id();
            $table->string('judulLowongan');
            $table->string('jenisLowongan');
            $table->string('tipeLowongan');
            $table->string('deskripsiLowongan');
            $table->string('kualifikasi');
            $table->string('benefit');
            $table->string('keahlian');
            $table->string('batasMulai');
            $table->string('batasAkhir');
            $table->string('perusahaan_id');
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lowongan');
    }
};
