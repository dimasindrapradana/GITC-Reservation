<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->foreignId('training_program_id')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        // Sengaja tidak mengembalikan kolom menjadi NOT NULL.
        // Ini mencegah kegagalan rollback jika sesi baru sudah
        // memiliki training_program_id = NULL.
    }
};
