<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $transactions = array(
            array('id' => '1','transaction_code' => 'TRX-202409-84XyW8pFf6','reference_code' => 'REF202409-gLoouC','donation' => '0.00','convenience_fee' => '10.00','sub_amount' => '1700.00','total_amount' => '1710.00','payment_mode' => 'N/A','payment_type' => 'event_registration','checkout_id' => 'd4644fc4-484f-4ed1-9962-2d1ed8052d58','payment_link' => 'https://payments-web-sandbox.maya.ph/v2/checkout?id=d4644fc4-484f-4ed1-9962-2d1ed8052d58','transaction_response_json' => '{"id": "61c94c1e-5897-4346-b9bb-86ac95f091e6", "amount": 945, "status": "PAYMENT_SUCCESS", "currency": "PHP", "requestReferenceNumber": "REF202409-gLoouC"}','status' => 'paid','created_at' => '2024-09-06 02:17:17','updated_at' => '2024-09-06 02:19:18'),
            array('id' => '2','transaction_code' => 'TRX-202409-qtLZEfqFSG','reference_code' => 'REF202409-YCROjN','donation' => '0.00','convenience_fee' => '10.00','sub_amount' => '1700.00','total_amount' => '1710.00','payment_mode' => 'N/A','payment_type' => 'event_registration','checkout_id' => '21b89625-5049-4da7-95e9-74db954acdff','payment_link' => 'https://payments-web-sandbox.maya.ph/v2/checkout?id=21b89625-5049-4da7-95e9-74db954acdff','transaction_response_json' => '{"id": "61c94c1e-5897-4346-b9bb-86ac95f091e6", "amount": 945, "status": "PAYMENT_SUCCESS", "currency": "PHP", "requestReferenceNumber": "REF202409-YCROjN"}','status' => 'paid','created_at' => '2024-09-06 03:22:30','updated_at' => '2024-09-06 03:23:19')
        );

        foreach($transactions as $transaction) {
            DB::table('transactions')->insert($transaction);
        }

        $user_one = User::where('id', 1)->first();
        $user_two = User::where('id', 2)->first();

        $event_registrations = array(
            array('id' => '1','registration_code' => 'REG24-09-NHZO5K4','transaction_id' => '1','event_id' => '1','mfc_id_number' => $user_one->mfc_id_number,'amount' => '1700.00','registered_by' => '1','registered_at' => '2024-09-06 02:17:17','created_at' => '2024-09-06 02:17:17','updated_at' => '2024-09-06 02:17:17'),
            array('id' => '2','registration_code' => 'REG24-09-ML9EYVJ','transaction_id' => '2','event_id' => '1','mfc_id_number' => $user_two->mfc_id_number,'amount' => '1700.00','registered_by' => '1','registered_at' => '2024-09-06 03:22:30','created_at' => '2024-09-06 03:22:30','updated_at' => '2024-09-06 03:22:30')
        );

        foreach ($event_registrations as $key => $registration) {
            DB::table('event_registrations')->insert($registration);
        }

        $event_user_details = array(
            array('id' => '1','event_registration_id' => '1','first_name' => 'Anna','last_name' => 'Adame','email' => 'admin@themesbrand.com','contact_number' => NULL,'address' => NULL,'created_at' => '2024-09-06 02:17:17','updated_at' => '2024-09-06 02:17:17'),
            array('id' => '2','event_registration_id' => '2','first_name' => 'George','last_name' => 'Steve','email' => 'georgesteve123@gmail.com','contact_number' => NULL,'address' => NULL,'created_at' => '2024-09-06 03:22:30','updated_at' => '2024-09-06 03:22:30')
        );

        foreach ($event_user_details as $key => $event_user_detail) {
            DB::table('event_user_details')->insert($event_user_detail);
        }
    }
}
