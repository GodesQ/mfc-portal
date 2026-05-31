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
            $table->string('ticket_logo')->nullable()->after('poster');
            $table->string('ticket_image')->nullable()->after('ticket_logo');
            $table->longText('ticket_instructions')->nullable()->after('ticket_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'ticket_logo',
                'ticket_image',
                'ticket_instructions',
            ]);
        });
    }
};
