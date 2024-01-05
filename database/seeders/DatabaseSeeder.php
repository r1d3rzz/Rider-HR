<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\CompanySetting;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        CompanySetting::truncate();

        CompanySetting::factory()->create();

        /*
        After run seeder file and insert
        - models_has_roles table -> 1 / User\Models\App / 1
        - create department data in website
        Enjoy :)
        */

        $permissions = [
            'view_employees', 'create_employee', 'edit_employee', 'remove_employee',
            'view_departments', 'create_department', 'edit_department', 'remove_department',
            'view_roles', 'create_role', 'edit_role', 'remove_role',
            'view_permissions', 'create_permission', 'edit_permission', 'remove_permission',
            'view_profile', 'create_profile', 'edit_profile', 'remove_profile',
            'view_company_setting', 'create_company_setting', 'edit_company_setting', 'remove_company_setting',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
            ]);
        }

        Role::create([
            'name' => 'HR',
        ]);
    }
}
