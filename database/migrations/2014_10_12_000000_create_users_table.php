<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('mfc_id_number')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('password');
            $table->text('avatar')->nullable();
            $table->string('contact_number')->unique()->nullable();
            $table->string('area')->nullable();
            $table->enum('chapter', User::$chapter)->nullable();
            $table->enum('gender', User::$gender)->nullable();
            $table->enum('status', User::$status)->nullable()->default('Active');
            $table->rememberToken();
            $table->timestamps();

        });

        $user = User::create(['first_name' => 'Anna', 'last_name' => 'Adame', 'email' => 'admin@mfcportal.com', 'password' => Hash::make('Test123!'), 'avatar' => 'avatar-1.jpg', 'created_at' => now(), 'email_verified_at' => now()], );
        $mfc_id_number = generateNewMFCId();

        $user->update([
            'mfc_id_number' => $mfc_id_number,
        ]);
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
