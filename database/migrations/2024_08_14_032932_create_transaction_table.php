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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code');
            $table->string('reference_code');
            $table->double('donation', 10,2)->default(0.00);
            $table->double('convenience_fee', 10,2)->default(0.00);
            $table->double('sub_amount', 10, 2)->default(0.00);
            $table->double('total_amount', 10, 2)->default(0.00);
            $table->string('payment_mode');
            $table->string('payment_type');
            $table->string('checkout_id')->nullable();
            $table->string('payment_link')->nullable();
            $table->json('transaction_response_json')->nullable();
            $table->string('status')->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
}; 
