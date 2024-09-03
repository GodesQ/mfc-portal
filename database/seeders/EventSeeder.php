<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            'id' => 1,
            'title' => 'World Singles Congress',
            'type' => '1',
            'section_id' => 3,
            'start_date' => '2024-09-03',
            'end_date' => '2024-09-05',
            'time' => '09:00:00',
            'location' => 'Alta Bohol Resort & Convention Center, Baclayon, Tagbilaran City, Bohol, Philippines',
            'latitude' => '9.634145699999998',
            'longitude' => '123.895317',
            'reg_fee' => '1700.00',
            'description' => '<p>THIS IS THE UNBREAKABLE FAITH AND LOVE OF GOD! 🔥</p><p>Are you still having a last congress syndrome??? Here\'s to recap everything that happened!!! Praise the Unbreakable God 🙏 May we go home with our yes and with the fire to continue His mission!</p>',
            'category' => null,
            'is_open_for_non_community' => 1,
            'is_enable_event_registration' => 1,
            'poster' => '1723773484_bohol.jpg',
            'status' => 'Active',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
