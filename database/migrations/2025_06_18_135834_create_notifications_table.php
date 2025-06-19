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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Type of notification (e.g., 'App\Notifications\UserFollowed')
            $table->morphs('notifiable'); // Polymorphic relationship (user, admin, etc.)
            $table->text('data'); // JSON data for notification details
            $table->timestamp('read_at')->nullable(); // When notification was read
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
