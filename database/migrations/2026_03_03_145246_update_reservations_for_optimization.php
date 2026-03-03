<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->index(['event_id', 'user_id']);
        });
        Schema::table('events', function (Blueprint $table) {
            $table->unsignedInteger('reservations_count')->index()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropIndex(['event_id', 'user_id']);
        });
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('reservations_count');
        });
    }
};
