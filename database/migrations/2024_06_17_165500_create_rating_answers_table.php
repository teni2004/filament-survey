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
        Schema::create('rating_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answer_id')                
                ->constrained('answers')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('rating');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating_answers');
    }
};
