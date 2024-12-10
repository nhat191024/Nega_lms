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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id');
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['closed', 'published', 'private', 'draft']);
            $table->string('level');
            $table->integer('duration');
            $table->integer('totalScore');
            $table->string('specialized');
            $table->string('subject');
            $table->string('topic');
            $table->date('start_date');
            $table->date('due_date');
            $table->boolean('auto_grade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
