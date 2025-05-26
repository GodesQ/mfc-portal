<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /******** AREA SERVANT USER ********/
        $member_one = User::create([
            'mfc_id_number' => generateNewMFCId(),
            'first_name' => 'George',
            'last_name' => 'Steve',
            'username' => 'membergeorge',
            'email' => 'member@mfcportal.com',
            'password' => Hash::make('Test123!'),
            'avatar' => 'avatar-2.jpg',
            'country_code' => 63,
            'contact_number' => '9171234567', // Philippine mobile number format
            'section_id' => 3,
            'area' => 'ncr_north',
            'created_at' => now(),
            'email_verified_at' => now()
        ]);

        $member_one->assignRole('member');
        $member_one->role_id = 7;
        $member_one->save();

        /******** CHAPTER SERVANT USER ********/
        $chapter_servant = User::create([
            'mfc_id_number' => generateNewMFCId(),
            'first_name' => 'Mark',
            'last_name' => 'Tone',
            'username' => 'mark',
            'email' => 'marktone1234@gmail.com',
            'password' => Hash::make('Test123!'),
            'avatar' => 'avatar-3.jpg',
            'country_code' => 63,
            'contact_number' => '9221234567', // Philippine mobile number format
            'section_id' => 3,
            'created_at' => now(),
            'email_verified_at' => now()
        ]);

        $chapter_servant->assignRole('chapter_servant');
        $chapter_servant->role_id = 4;
        $chapter_servant->save();

        /******** UNIT SERVANT USER ********/
        $unit_servant = User::create([
            'mfc_id_number' => generateNewMFCId(),
            'first_name' => 'James',
            'last_name' => 'Yue',
            'username' => 'james',
            'email' => 'jamesyue443@gmail.com',
            'password' => Hash::make('Test123!'),
            'avatar' => 'avatar-4.jpg',
            'country_code' => 63,
            'contact_number' => '9331234567', // Philippine mobile number format
            'section_id' => 5,
            'created_at' => now(),
            'email_verified_at' => now()
        ]);

        $unit_servant->assignRole('unit_servant');
        $unit_servant->role_id = 5;
        $unit_servant->save();

        /******** HOUSEHOLD SERVANT USER ********/
        $household_servant = User::create([
            'mfc_id_number' => generateNewMFCId(),
            'first_name' => 'Robert',
            'last_name' => 'Mendez',
            'username' => 'robert',
            'email' => 'robertmendez13@gmail.com',
            'password' => Hash::make('Test123!'),
            'avatar' => 'avatar-5.jpg',
            'country_code' => 63,
            'contact_number' => '9441234567', // Philippine mobile number format
            'section_id' => 3,
            'created_at' => now(),
            'email_verified_at' => now()
        ]);

        $household_servant->assignRole('household_servant');
        $household_servant->role_id = 6;
        $household_servant->save();

        /******** MEMBER USER ********/
        $member = User::create([
            'mfc_id_number' => generateNewMFCId(),
            'first_name' => 'Charles',
            'last_name' => 'Jamis',
            'username' => 'charles_jamis',
            'email' => 'charlesjamis@gmail.com',
            'password' => Hash::make('Test123!'),
            'avatar' => 'avatar-6.jpg',
            'country_code' => 63,
            'contact_number' => '9551234567', // Philippine mobile number format
            'section_id' => 3,
            'created_at' => now(),
            'email_verified_at' => now()
        ]);

        UserDetail::create([
            'user_id' => $member->id,
            'god_given_skills' => [
                "Prayer Leading",
                "Website Development and Maintenance"
            ],
            'birthday' => '2003-10-15',
        ]);

        $member->assignRole('member');
        $member->role_id = 7;
        $member->save();
    }
}
