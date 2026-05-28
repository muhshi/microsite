<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin'),
            ]
        );

        // Ensure roles exist
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $pegawaiRole = Role::firstOrCreate(['name' => 'pegawai']);

        // Define permissions to assign to pegawai
        $pegawaiPermissions = [
            // Dashboard widgets
            'View:ActiveMicrositesTable',
            'View:MicrositeStatsOverview',

            // Categories
            'ViewAny:Category',
            'View:Category',

            // Series
            'ViewAny:Series',
            'View:Series',

            // Microsites
            'ViewAny:Microsite',
            'View:Microsite',
            'Create:Microsite',
            'Update:Microsite',
            'Delete:Microsite',
            'Restore:Microsite',
            'Replicate:Microsite',

            // Microsite Links/Sections
            'Create:MicrositeLink',
            'Update:MicrositeLink',
            'Delete:MicrositeLink',
            'ViewAny:MicrositeLink',
            'View:MicrositeLink',

            // Short Links
            'ViewAny:ShortLink',
            'View:ShortLink',
            'Create:ShortLink',
            'Update:ShortLink',
            'Delete:ShortLink',
        ];

        foreach ($pegawaiPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $pegawaiRole->givePermissionTo($permission);
        }

        // Assign super admin role to the admin user
        $admin->assignRole($superAdminRole);
    }
}
