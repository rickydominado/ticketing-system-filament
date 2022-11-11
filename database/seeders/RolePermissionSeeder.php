<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // User Permissions
        Permission::create(['name' => 'create:user']);
        Permission::create(['name' => 'read:user']);
        Permission::create(['name' => 'update:user']);
        Permission::create(['name' => 'delete:user']);

        // Inquiry Permissions
        Permission::create(['name' => 'create:inquiry']);
        Permission::create(['name' => 'read:inquiry']);
        Permission::create(['name' => 'update:inquiry']);
        Permission::create(['name' => 'delete:inquiry']);

        // Category Permissions
        Permission::create(['name' => 'create:category']);
        Permission::create(['name' => 'read:category']);
        Permission::create(['name' => 'update:category']);
        Permission::create(['name' => 'delete:category']);

        // Admin Permissions
        Permission::create(['name' => 'read:admin']);
        Permission::create(['name' => 'update:admin']);

        // Super Admin Role
        $superAdminRole = Role::create(['name' => 'super-admin']);

        // Admin Role
        $adminRole = Role::create(['name' => 'admin'])
            ->givePermissionTo([
                'create:user',
                'read:user',
                'update:user',
                'delete:user',
                'read:inquiry',
                'update:inquiry',
                'delete:inquiry',
                'create:category',
                'read:category',
                'update:category',
                'delete:category',
            ]);

        // Agent Role
        $agentRole = Role::create(['name' => 'agent'])
            ->givePermissionTo([
                'read:inquiry',
                'update:inquiry',
            ]);

        // Create Super Admin User
        User::factory()->create([
            'name' => 'super admin',
            'email' => 'super@admin.com',
            'is_admin' => true,
        ])->assignRole($superAdminRole);

        // Create Admin User
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'is_admin' => true,
        ])->assignRole($adminRole);

        // Create Agents
        $agents = User::factory()->count(10)->create();

        foreach ($agents as $agent) {
            $agent->assignRole($agentRole);
        }
    }
}
