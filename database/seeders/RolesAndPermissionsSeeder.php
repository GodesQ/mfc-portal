<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $permissions = [
            'create-role',
            'edit-role',
            'delete-role',
            'view-role',
            'create-permission',
            'edit-permission',
            'delete-permission',
            'view-permission',
            'create-dashboard',
            'edit-dashboard',
            'delete-dashboard',
            'view-dashboard',
            'create-member',
            'edit-member',
            'delete-member',
            'view-member',
            'create-household',
            'edit-household',
            'delete-household',
            'view-household',
            'create-area',
            'edit-area',
            'delete-area',
            'view-area',
            'create-event',
            'edit-event',
            'delete-event',
            'view-event',
            'view-tithes',
            'create-tithes',
        ];

        // Looping and Inserting Array's Permissions into Permission Table
        foreach($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $superadmin = Role::create(['name' => 'super_admin']);
        $admin = Role::create(['name' => 'admin']);
        $areaServant = Role::create(['name' => 'area_servant']);
        $chapterServant = Role::create(['name' => 'chapter_servant']);
        $unitServant = Role::create(['name' => 'unit_servant']);
        $householdServant = Role::create(['name' => 'household_servant']);
        $member = Role::create(['name' => 'member']);

        $superadmin->givePermissionTo([
            'create-role',
            'edit-role',
            'delete-role',
            'view-role',
            'create-permission',
            'edit-permission',
            'delete-permission',
            'view-permission',
            'create-dashboard',
            'edit-dashboard',
            'delete-dashboard',
            'view-dashboard',
            'create-member',
            'edit-member',
            'delete-member',
            'view-member',
            'create-household',
            'edit-household',
            'delete-household',
            'view-household',
            'create-area',
            'edit-area',
            'delete-area',
            'view-area',
            'create-event',
            'edit-event',
            'delete-event',
            'view-event',
            'create-tithes',
            'view-tithes',
        ]);

        $admin->givePermissionTo([
            'view-role',
            'create-dashboard',
            'edit-dashboard',
            'delete-dashboard',
            'view-dashboard',
            'create-member',
            'view-member',
            'edit-member',
            'delete-member',
            'create-household',
            'view-household',
            'edit-household',
            'delete-household',
            'create-area',
            'view-area',
            'edit-area',
            'delete-area',
            'create-event',
            'view-event',
            'edit-event',
            'delete-event',
            'create-tithes',
            'view-tithes',
        ]);

        $areaServant->givePermissionTo([
            'create-event',
            'view-event',
            'edit-event',
            'delete-event',
            'create-household',
            'view-household',
            'edit-household',
            'delete-household',
            'create-member',
            'edit-member',
            'delete-member',
            'view-member',
            'create-tithes',
        ]);

        $chapterServant->givePermissionTo([
            'create-event',
            'view-event',
            'edit-event',
            'delete-event',
            'create-household',
            'view-household',
            'edit-household',
            'delete-household',
            'create-member',
            'edit-member',
            'delete-member',
            'view-member',
            'create-tithes',
        ]);

        $unitServant->givePermissionTo([
            'create-event',
            'view-event',
            'edit-event',
            'delete-event',
            'create-household',
            'view-household',
            'edit-household',
            'delete-household',
            'create-member',
            'edit-member',
            'delete-member',
            'view-member',
            'create-tithes',
        ]);

        $householdServant->givePermissionTo([
            'view-event',
            'create-member',
            'edit-member',
            'delete-member',
            'view-member',
            'create-tithes',
        ]);

        $member->givePermissionTo([
            'view-event',
            'create-tithes',
        ]);

        $user = User::find(1);

        $user->assignRole('super_admin');
        $user->role_id = 1;
        $user->save();

    }
}
