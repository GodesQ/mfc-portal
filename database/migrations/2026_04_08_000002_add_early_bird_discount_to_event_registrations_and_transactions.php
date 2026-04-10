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
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->decimal('early_bird_discount', 10, 2)->default(0.00)->after('amount');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('early_bird_discount', 10, 2)->default(0.00)->after('sub_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('early_bird_discount');
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn('early_bird_discount');
        });
    }
};
