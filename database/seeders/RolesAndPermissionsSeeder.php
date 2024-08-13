<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Create Permissions
        Permission::create(['name' => 'view products']);
        Permission::create(['name' => 'create products']);
        Permission::create(['name' => 'edit products']);
        Permission::create(['name' => 'delete products']);

        // Create Roles and Assign Permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo('view products');
        $adminRole->givePermissionTo('create products');
        $adminRole->givePermissionTo('edit products');
        $adminRole->givePermissionTo('delete products');

        $editorRole = Role::create(['name' => 'editor']);
        $editorRole->givePermissionTo('view products');
        $editorRole->givePermissionTo('edit products');

        $viewerRole = Role::create(['name' => 'viewer']);
        $viewerRole->givePermissionTo('view products');
    }
}
