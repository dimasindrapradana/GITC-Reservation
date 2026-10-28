<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->foreignId('training_schedule_id')
                ->nullable()
                ->after('training_program_id')
                ->constrained('training_schedules')
                ->restrictOnDelete();

            $table->index(
                ['training_schedule_id', 'session_date'],
                'training_sessions_schedule_date_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->dropIndex('training_sessions_schedule_date_index');

            $table->dropConstrainedForeignId('training_schedule_id');
        });
    }
};
