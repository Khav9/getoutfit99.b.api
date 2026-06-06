<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [

            // Users
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Categories
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',

            // Products
            'view products',
            'create products',
            'edit products',
            'delete products',

            //roles
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',

            //permissions
            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
        ];

        // create permissions only if not exists
        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // create roles only if not exists
        $admin = Role::findOrCreate('admin', 'web');
        $editor = Role::findOrCreate('editor', 'web');
        $cashier = Role::findOrCreate('cashier', 'web');

        // sync permissions (replaces old permissions safely)

        $admin->syncPermissions([
            'view users',
            'create users',
            'edit users',
            'delete users',

            'view categories',
            'create categories',
            'edit categories',
            'delete categories',

            'view products',
            'create products',
            'edit products',
            'delete products',

            'view roles',
            'create roles',
            'edit roles',
            'delete roles',

            'view permissions',
            'create permissions',
            'edit permissions',
            'delete permissions',
        ]);

        $editor->syncPermissions([
            'view users',
            'view categories',
            'view products',
        ]);

        $cashier->syncPermissions([
            'view categories',
            'view products',
        ]);
    }
}