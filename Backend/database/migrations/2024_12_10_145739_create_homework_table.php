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
        Schema::create('homework', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->enum('type', ['homework', 'quiz']);
            $table->unsignedBigInteger('assignment_id')->nullable();
            $table->string('link')->nullable();
            $table->dateTime('start_datetime')->nullable();
            $table->dateTime('due_datetime')->nullable();
            $table->integer('duration')->nullable();
            $table->boolean('auto_grade')->default(true);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homework');
    }
};