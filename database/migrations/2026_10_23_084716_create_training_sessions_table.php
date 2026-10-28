<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('training_program_id')
                ->constrained('training_programs')
                ->cascadeOnDelete();

            $table->foreignId('training_room_id')
                ->constrained('training_rooms')
                ->restrictOnDelete();

            $table->string('subject', 200);
            $table->string('class_name', 150);
            $table->string('instructor_name', 150);
            $table->unsignedInteger('participant_count');

            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'session_date',
                'start_time',
                'end_time',
            ]);

            $table->index([
                'training_room_id',
                'session_date',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
