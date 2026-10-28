<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('building_id')
                ->constrained('buildings')
                ->restrictOnDelete();

            $table->date('schedule_date');

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['building_id', 'schedule_date'],
                'training_schedules_building_date_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_schedules');
    }
};
