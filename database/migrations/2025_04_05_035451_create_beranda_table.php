<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beranda', function (Blueprint $table) {
            $table->id();
            $table->text('deskripsi_beranda')->default('Selamat datang di Laman Career Alumni Information System, Institut Teknologi Del. Melalui website ini, Menghadirkan berbagai layanan, mulai dari portal karir, database alumni, hingga layanan bimbingan karir yang membantu Anda meraih peluang terbaik di dunia profesional.');
            $table->timestamps();
        });

        // Insert default row (id = 1)
        DB::table('beranda')->insert([
            'deskripsi_beranda' => 'Selamat datang di Laman Career Alumni Information System, Institut Teknologi Del. Melalui website ini, Menghadirkan berbagai layanan, mulai dari portal karir, database alumni, hingga layanan bimbingan karir yang membantu Anda meraih peluang terbaik di dunia profesional.',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('beranda');
    }
};
