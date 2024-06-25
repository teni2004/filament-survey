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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_response_id')                
                ->constrained('survey_responses')
                ->cascadeOnDelete();
            $table->foreignId('question_id')                
                ->constrained('questions')
                ->cascadeOnDelete();
            $table->enum('type', ['rating', 'yes-no', 'multiple-choice', 'select-one', 'free-form']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
