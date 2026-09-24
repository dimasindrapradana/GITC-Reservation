<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('target_type', 50)
                ->nullable()
                ->after('message');

            $table->unsignedBigInteger('target_id')
                ->nullable()
                ->after('target_type');

            $table->index(
                ['target_type', 'target_id'],
                'notifications_target_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_target_index');
            $table->dropColumn([
                'target_type',
                'target_id',
            ]);
        });
    }
};