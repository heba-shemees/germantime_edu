<?php
// ============================================================
// FILE: database/migrations/2024_01_01_000002_create_test_questions_table.php
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('test_questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->json('options');          // {"a": "...", "b": "...", "c": "...", "d": "..."}
            $table->char('correct_answer', 1); // a / b / c / d
            $table->unsignedTinyInteger('points')->default(10);
            $table->unsignedSmallInteger('order')->default(0);
            $table->enum('level_hint', ['A1', 'A2', 'B1', 'B2'])->nullable();
            $table->timestamps();

            $table->index('order');
        });

        // ========================================================
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->json('answers');
            $table->unsignedTinyInteger('score');
            $table->enum('level', ['A1', 'A2', 'B1', 'B2']);
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->timestamps();
        });

        // ========================================================
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique();
            $table->string('name', 100);
            $table->string('whatsapp', 15);
            $table->enum('level', ['A1', 'A2', 'B1', 'B2', 'private']);
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('test_result_id')->nullable()->constrained()->nullOnDelete();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        // ========================================================
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->enum('method', ['vodafone_cash', 'instapay']);
            $table->unsignedInteger('amount');
            $table->string('proof_url')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        // ========================================================
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('student_name', 80);
            $table->text('body');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('photo_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('test_results');
        Schema::dropIfExists('test_questions');
    }
};
