<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('lowongan', function (Blueprint $table) {
           
            $table->text('deskripsiLowongan')->change();

            $table->text('kualifikasi')->change();

            $table->text('benefit')->change();
        });
    }

    public function down()
    {
        Schema::table('lowongan', function (Blueprint $table) {
            
            $table->string('deskripsiLowongan')->change();
            $table->string('kualifikasi')->change();
            $table->string('benefit')->change();
        });
    }
};
