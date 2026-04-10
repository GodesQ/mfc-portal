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
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('is_early_bird_enabled')->default(false)->after('reg_fee');
            $table->decimal('early_bird_discount', 10, 2)->default(0.00)->after('is_early_bird_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['is_early_bird_enabled', 'early_bird_discount']);
        });
    }
};
