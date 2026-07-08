<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('level', ['A1', 'A2', 'B1', 'B2']);
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('duration_weeks')->default(8);
            $table->unsignedTinyInteger('sessions_per_week')->default(2);
            $table->unsignedSmallInteger('session_duration_minutes')->default(60);
            $table->unsignedInteger('price')->default(0);
            $table->json('schedule')->nullable();
            $table->json('learning_outcomes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
