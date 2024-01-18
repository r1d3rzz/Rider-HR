<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\CheckinCheckout;
use App\Models\CompanySetting;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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
        close roles permissions in RoleController and update HR roles permission in website
        Enjoy :)
        */

        // $permissions = [
        //     'view_employees', 'create_employee', 'edit_employee', 'remove_employee',
        //     'view_departments', 'create_department', 'edit_department', 'remove_department',
        //     'view_roles', 'create_role', 'edit_role', 'remove_role',
        //     'view_permissions', 'create_permission', 'edit_permission', 'remove_permission',
        //     'view_profile', 'create_profile', 'edit_profile', 'remove_profile',
        //     'view_company_setting', 'create_company_setting', 'edit_company_setting', 'remove_company_setting',
        //     'view_attendances', 'create_attendance', 'edit_attendance', 'remove_attendance',
        // ];

        // foreach ($permissions as $permission) {
        //     Permission::create([
        //         'name' => $permission,
        //     ]);
        // }

        // Role::create([
        //     'name' => 'HR',
        // ]);

        $employees = User::all();
        foreach ($employees as $employee) {
            $periods = CarbonPeriod::create('2024-01-01', '2024-12-31');
            foreach ($periods as $period) {
                if ($period->format('D') !== 'Sat' && $period->format('D') !== 'Sun') {
                    CheckinCheckout::create([
                        'user_id' => $employee->id,
                        'date' => $period->format('Y-m-d'),
                        'checkin_time' => Carbon::parse($period->format('Y-m-d') . ' ' . '09:00:00')->subMinute(rand(5, 55)),
                        'checkout_time' => Carbon::parse($period->format('Y-m-d') . ' ' . '18:00:00')->addMinute(rand(1, 55)),
                    ]);
                }
            }
        }
    }
}
