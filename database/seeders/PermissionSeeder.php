<?php

namespace Database\Seeders;

use App\Constants\Permission as PermissionConstant;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use \App\Constants\Role as RoleConstant;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminGuard = Guard::getDefaultName(Admin::class);
        // Seeding roles
        /** @var Role $adminRole */
        $adminRole = Role::query()->firstOrCreate(['name' => RoleConstant::ROLE_ADMIN, 'guard_name' => $adminGuard]);
        /** @var Role $staff */
        $staff = Role::query()->firstOrCreate(['name' => RoleConstant::ROLE_STAFF, 'guard_name' => $adminGuard]);

        foreach (PermissionConstant::getAllPermissions() as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission, 'guard_name' => $adminGuard]);
        }

        $adminRole->syncPermissions(PermissionConstant::getAdminPermissions());
        $staff->syncPermissions(PermissionConstant::getStaffPermissions());
    }
}
