<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'super_admin')->firstOrFail();

        $user = User::firstOrCreate(
            ['email' => 'admin@mfcportal.com'],
            [
                'mfc_id_number' => generateNewMFCId(),
                'first_name' => 'Anna',
                'last_name' => 'Adame',
                'username' => 'admin',
                'password' => Hash::make('Test123!'),
                'avatar' => 'avatar-1.jpg',
                'email_verified_at' => now(),
            ]
        );

        if (! $user->mfc_id_number) {
            $user->mfc_id_number = generateNewMFCId();
        }

        $user->syncRoles([$superAdminRole]);
        $user->role_id = $superAdminRole->id;
        $user->save();
    }
}
