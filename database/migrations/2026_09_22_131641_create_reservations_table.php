<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->string('reservation_number', 50)->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('room_id')
                ->nullable()
                ->constrained('rooms')
                ->restrictOnDelete();

            $table->foreignId('training_room_id')
                ->nullable()
                ->constrained('training_rooms')
                ->restrictOnDelete();

            $table->foreignId('field_id')
                ->nullable()
                ->constrained('fields')
                ->restrictOnDelete();

            $table->dateTime('starts_at');
            $table->dateTime('ends_at');

            $table->unsignedInteger('total_person');

            $table->string('event_name');

            $table->string('booker_name');

            $table->text('instructor')->nullable();

            $table->text('description');

            $table->string('status', 20)->default('PENDING');

            $table->text('rejection_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};