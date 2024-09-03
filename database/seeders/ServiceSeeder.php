<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Servant Council, National Coordinator, Section Coordinator, Provincial Coordinator, Area Servant, Chapter Servant, Unit Servant, Household Servant, Mission Volunteer, Full Time

        Service::create([
            'name' => 'Servant Council',
            'type' => 'MFC',
        ]);

        Service::create([
            'name' => 'National Coordinator',
            'type' => 'MFC',
        ]);

        Service::create([
            'name' => 'Section Coordinator',
            'type' => 'MFC',
        ]);

        Service::create([
            'name' => 'Provincial Coordinator',
            'type' => 'MFC',
        ]);

        Service::create([
            'name' => 'Area Servant',
            'type' => 'MFC',
        ]);

        Service::create([
            'name' => 'Chapter Servant',
            'type' => 'MFC',
        ]);

        Service::create([
            'name' => 'Unit Servant',
            'type' => 'MFC',
        ]);

        Service::create([
            'name' => 'Household Servant',
            'type' => 'MFC',
        ]);

        Service::create([
            'name' => 'Mission Volunteer',
            'type' => 'MFC',
        ]);

        Service::create([
            'name' => 'Full Time',
            'type' => 'MFC',
        ]);

        Service::create([
            'name' => 'LCSC Coordinator',
            'type' => 'LCSC',
        ]);

        Service::create([
            'name' => 'Pillar Head',
            'type' => 'LCSC',
        ]);

        Service::create([
            'name' => 'Area Coordinator',
            'type' => 'LCSC',
        ]);

        Service::create([
            'name' => 'Provincial Coordinator',
            'type' => 'LCSC',
        ]);

        Service::create([
            'name' => 'Full Time',
            'type' => 'LCSC',
        ]);

        Service::create([
            'name' => 'Mission Volunteer',
            'type' => 'LCSC',
        ]);

    }
}
