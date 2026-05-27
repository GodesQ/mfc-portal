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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('ticket_id')
                ->nullable()
                ->after('received_from_id')
                ->constrained('tickets')
                ->nullOnDelete();
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->foreignId('ticket_id')
                ->nullable()
                ->after('event_id')
                ->constrained('tickets')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ticket_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ticket_id');
        });
    }
};
