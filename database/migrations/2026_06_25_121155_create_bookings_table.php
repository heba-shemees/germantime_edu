<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name');
            $table->string('whatsapp');
            $table->string('level');
            $table->unsignedBigInteger('schedule_id')->nullable();
            $table->text('notes')->nullable();
            $table->string('receipt_path')->nullable();
            $table->enum('status', [
                'pending',
                'uploaded',
                'confirmed',
                'cancelled',
            ])->default('pending');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
