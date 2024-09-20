<?php

use App\Models\User;
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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->string('type')->nullable();
            $table->json('section_ids')->nullable();
            // $table->foreignId('section_id')->nullable()->constrained('sections')->cascadeOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->time('time')->nullable();
            $table->string('location')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->decimal('reg_fee')->nullable();
            $table->longText('description')->nullable();
            $table->string('area')->nullable();
            $table->string('category')->nullable();
            $table->boolean('is_open_for_non_community')->default(false);
            $table->boolean('is_enable_event_registration')->default(false);
            $table->string('poster')->nullable();
            $table->enum('status', User::$status)->nullable()->default('Active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
