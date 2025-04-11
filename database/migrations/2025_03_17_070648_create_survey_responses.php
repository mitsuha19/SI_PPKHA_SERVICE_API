<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_sections_id')->constrained()->onDelete('cascade');
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->text('response'); // Stores the user’s answer (text or JSON)
            $table->timestamps(); // Automatically adds created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('survey_responses');
    }
};
