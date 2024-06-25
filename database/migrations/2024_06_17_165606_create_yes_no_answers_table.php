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
        Schema::create('yes_no_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answer_id')
                ->constrained('answers')
                ->cascadeOnDelete();
            $table->boolean('choice');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yes_no_answers');
    }
};
