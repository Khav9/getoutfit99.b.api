<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run role/permission seeder first
        $this->call([
            RolePermissionSeeder::class,
        ]);
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@getoutfit99.com',
        ]);

        // Assign admin role
        $admin = Role::findOrCreate('admin', 'web');
        $user->assignRole($admin);
    }
}
