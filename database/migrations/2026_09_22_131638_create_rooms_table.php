<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            $table->foreignId('building_id')
                ->constrained('buildings')
                ->restrictOnDelete();

            $table->string('name', 100);
            $table->unsignedInteger('capacity')->default(0);
            $table->unsignedInteger('lcd_count')->default(0);
            $table->unsignedInteger('whiteboard_count')->default(0);
            $table->string('status', 20)->default('AVAILABLE');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};