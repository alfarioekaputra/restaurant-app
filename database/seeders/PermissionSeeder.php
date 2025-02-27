<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'create menus']);
        Permission::create(['name' => 'edit menus']);
        Permission::create(['name' => 'delete menus']);
        Permission::create(['name' => 'publish menus']);
        Permission::create(['name' => 'unpublish menus']);

        Permission::create(['name' => 'edit orders']);
        Permission::create(['name' => 'view orders']);

        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'staff']);
        $role1->givePermissionTo('create menus');
        $role1->givePermissionTo('edit menus');
        $role1->givePermissionTo('delete menus');

        $role2 = Role::create(['name' => 'admin']);
        $role2->givePermissionTo('publish menus');
        $role2->givePermissionTo('unpublish menus');
        $role2->givePermissionTo('create users');
        $role2->givePermissionTo('edit users');

        $role3 = Role::create(['name' => 'cashier']);
        $role3->givePermissionTo('edit orders');
        $role3->givePermissionTo('view orders');

        $role4 = Role::create(['name' => 'Super-Admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = \App\Models\User::factory()->create([
            'name' => 'Staff',
            'email' => 'staff@example.com',
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::factory()->create([
            'name' => 'Cashier',
            'email' => 'cashier@example.com',
        ]);
        $user->assignRole($role3);

        $user = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);
        $user->assignRole($role2);

        $user = \App\Models\User::factory()->create([
            'name' => 'Super-Admin',
            'email' => 'superadmin@example.com',
        ]);
        $user->assignRole($role4);
    }
}
