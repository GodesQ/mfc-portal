<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('event_user_details', function (Blueprint $table) {
            $table->string('tshirt_size')->nullable()->after('contact_number');
            $table->string('mfc_section')->nullable()->after('tshirt_size');
            $table->string('area')->nullable()->after('mfc_section');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_user_details', function (Blueprint $table) {
            $table->dropColumn(['tshirt_size', 'mfc_section', 'area']);
        });
    }
};
