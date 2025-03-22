<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Buat permissions
        $permissions = [
            ['name' => 'Access Dashboard', 'slug' => 'access-dashboard'],
            ['name' => 'Manage Products', 'slug' => 'manage-products'],
            ['name' => 'Manage Categories', 'slug' => 'manage-categories'],
            ['name' => 'Manage Suppliers', 'slug' => 'manage-suppliers'],
            ['name' => 'Manage Customers', 'slug' => 'manage-customers'],
            ['name' => 'Manage Sales', 'slug' => 'manage-sales'],
            ['name' => 'Manage Purchases', 'slug' => 'manage-purchases'],
            ['name' => 'Access Reports', 'slug' => 'access-reports'],
            ['name' => 'Manage Users', 'slug' => 'manage-users'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Buat roles
        $adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin'
        ]);

        $kasirRole = Role::create([
            'name' => 'Cashier',
            'slug' => 'cashier'
        ]);

        // Assign permissions ke roles
        $adminRole->permissions()->attach(Permission::all());

        $kasirRole->permissions()->attach(
            Permission::whereIn('slug', [
                'access-dashboard',
                'manage-sales',
                'manage-customers',
                'access-reports'
            ])->get()
        );

        // Buat admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $admin->roles()->attach($adminRole);
    }
}
