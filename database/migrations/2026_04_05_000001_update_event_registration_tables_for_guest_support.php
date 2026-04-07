<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('event_id')->constrained('users')->nullOnDelete();
            $table->string('mfc_id_number')->nullable()->change();
        });

        Schema::table('event_user_details', function (Blueprint $table) {
            $table->enum('user_type', ['primary', 'normal'])->default('normal')->after('event_registration_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payer_first_name')->nullable()->after('received_from_id');
            $table->string('payer_last_name')->nullable()->after('payer_first_name');
            $table->string('payer_email')->nullable()->after('payer_last_name');
            $table->string('payer_contact_number', 20)->nullable()->after('payer_email');
        });

        $userIdsByMfcNumber = DB::table('users')
            ->whereNotNull('mfc_id_number')
            ->pluck('id', 'mfc_id_number');

        DB::table('event_registrations')
            ->select('id', 'mfc_id_number')
            ->orderBy('id')
            ->get()
            ->each(function ($registration) use ($userIdsByMfcNumber) {
                $userId = $registration->mfc_id_number
                    ? ($userIdsByMfcNumber[$registration->mfc_id_number] ?? null)
                    : null;

                DB::table('event_registrations')
                    ->where('id', $registration->id)
                    ->update(['user_id' => $userId]);
            });

        DB::table('event_user_details')
            ->whereNull('user_type')
            ->update(['user_type' => 'normal']);

        $usersById = DB::table('users')
            ->select('id', 'first_name', 'last_name', 'email', 'contact_number')
            ->get()
            ->keyBy('id');

        DB::table('transactions')
            ->select('id', 'received_from_id')
            ->orderBy('id')
            ->get()
            ->each(function ($transaction) use ($usersById) {
                $payer = $transaction->received_from_id
                    ? $usersById->get($transaction->received_from_id)
                    : null;

                if (! $payer) {
                    return;
                }

                DB::table('transactions')
                    ->where('id', $transaction->id)
                    ->update([
                        'payer_first_name' => $payer->first_name,
                        'payer_last_name' => $payer->last_name,
                        'payer_email' => $payer->email,
                        'payer_contact_number' => $payer->contact_number,
                    ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'payer_first_name',
                'payer_last_name',
                'payer_email',
                'payer_contact_number',
            ]);
        });

        Schema::table('event_user_details', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->string('mfc_id_number')->nullable(false)->change();
        });
    }
};
