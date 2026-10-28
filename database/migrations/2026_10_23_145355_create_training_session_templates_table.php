<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_session_templates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('building_id')
                ->constrained('buildings')
                ->restrictOnDelete();

            $table->unsignedSmallInteger('session_number');
            $table->time('start_time');
            $table->time('end_time');

            $table->timestamps();

            $table->unique(
                ['building_id', 'session_number'],
                'training_session_templates_building_session_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_session_templates');
    }
};
