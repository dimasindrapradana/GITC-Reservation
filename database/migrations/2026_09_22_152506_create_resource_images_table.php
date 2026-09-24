<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_images', function (Blueprint $table) {
            $table->id();

            $table->string('resource_type');
            $table->unsignedBigInteger('resource_id');

            $table->string('file');
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(
                ['resource_type', 'resource_id'],
                'resource_images_resource_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_images');
    }
};