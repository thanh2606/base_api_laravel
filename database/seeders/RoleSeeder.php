<?php

namespace Database\Seeders;

use App\Enums\EnumRole;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(['name' => EnumRole::SUPER_ADMIN->name]);
        $admin = Role::firstOrCreate(['name' => EnumRole::ADMIN->name]);
        $editor = Role::firstOrCreate(['name' => EnumRole::EDITOR->name]);

        $allPermission = Permission::all();
        $superAdmin->givePermissions($allPermission->pluck('id')->toArray());

        $adminPermission = Permission::query()
            ->whereIn('name', [
                'user-view',
                'user-create',
                'user-edit',
                'user-delete',
            ])->pluck('id')->toArray();

        $admin->givePermissions($adminPermission);
    }
}
