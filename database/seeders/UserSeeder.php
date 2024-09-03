<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $area_servant = User::create([
            'first_name' => 'George', 
            'last_name' => 'Steve', 
            'email' => 'georgesteve123@gmail.com', 
            'password' => Hash::make('12345678'), 
            'avatar' => 'avatar-2.jpg', 
            'section_id' => 3,
            'created_at' => now(), 
            'email_verified_at' => now()
        ]);

        $area_servant->update([
            'mfc_id_number' => generateNextMfcId(),
        ]);

        $area_servant->assignRole('area_servant');
        $area_servant->role_id = 3;
        $area_servant->save();

        /******** CHAPTER SERVANT USER ********/
        $chapter_servant = User::create([
            'first_name' => 'Mark', 
            'last_name' => 'Tone', 
            'email' => 'marktone1234@gmail.com', 
            'password' => Hash::make('12345678'), 
            'avatar' => 'avatar-3.jpg', 
            'section_id' => 3,
            'created_at' => now(), 
            'email_verified_at' => now()
        ]);

        $chapter_servant->update([
            'mfc_id_number' => generateNextMfcId(),
        ]);

        $chapter_servant->assignRole('chapter_servant');
        $chapter_servant->role_id = 4;
        $chapter_servant->save();

        /******** UNIT SERVANT USER ********/
        $unit_servant = User::create([
            'first_name' => 'James', 
            'last_name' => 'Yue', 
            'email' => 'jamesyue443@gmail.com', 
            'password' => Hash::make('12345678'), 
            'avatar' => 'avatar-4.jpg', 
            'section_id' => 5,
            'created_at' => now(), 
            'email_verified_at' => now()
        ]);

        $unit_servant->update([
            'mfc_id_number' => generateNextMfcId(),
        ]);

        $unit_servant->assignRole('unit_servant');
        $unit_servant->role_id = 5;
        $unit_servant->save();

        /******** HOUSEHOLD SERVANT USER ********/
        $household_servant = User::create([
            'first_name' => 'Robert', 
            'last_name' => 'Mendez', 
            'email' => 'robertmendez13@gmail.com', 
            'password' => Hash::make('12345678'), 
            'avatar' => 'avatar-5.jpg', 
            'section_id' => 3,
            'created_at' => now(), 
            'email_verified_at' => now()
        ]);

        $household_servant->update([
            'mfc_id_number' => generateNextMfcId(),
        ]);

        $household_servant->assignRole('household_servant');
        $household_servant->role_id = 6;
        $household_servant->save();

        /******** MEMBER USER ********/
        $member = User::create([
            'first_name' => 'Charles', 
            'last_name' => 'Jamis', 
            'email' => 'charlesjamis@gmail.com', 
            'password' => Hash::make('12345678'), 
            'avatar' => 'avatar-6.jpg', 
            'section_id' => 3,
            'created_at' => now(), 
            'email_verified_at' => now()
        ]);

        $member->update([
            'mfc_id_number' => generateNextMfcId(),
        ]);

        $member->assignRole('member');
        $member->role_id = 7;
        $member->save();
    }
}
