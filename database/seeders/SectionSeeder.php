<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Service::create([
        //     'name' => 'Area Servant',
        // ]);
        // Service::create([
        //     'name' => 'Chapter Servant',
        // ]);
        // Service::create([
        //     'name' => 'Unit Servant',
        // ]);
        // Service::create([
        //     'name' => 'Household Servant',
        // ]);

        Section::create([
            'name' => 'kids',
        ]);

        Section::create([
            'name' => 'youth',
        ]);

        Section::create([
            'name' => 'singles',
        ]);

        Section::create([
            'name' => 'handmaids',
        ]);

        Section::create([
            'name' => 'servants',
        ]);

        Section::create([
            'name' => 'couples',
        ]);

    }
}
