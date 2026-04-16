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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['general', 'debate', 'impromptu_speech', 'essay', 'arabic_passage', 'english_passage']);
            $table->string('subject')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('duration_minutes')->default(30);
            $table->integer('question_timer')->default(30);
            $table->integer('points_per_question')->default(2);
            $table->boolean('is_live')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
