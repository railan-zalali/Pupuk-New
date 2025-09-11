<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin Test',
                'password' => bcrypt('password')
            ]
        );

        // Get existing admin role
        $adminRole = Role::where('slug', 'admin')->first();
        
        if (!$adminRole) {
            $adminRole = Role::create([
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrator with full access'
            ]);
        }

        // Create permissions
        $permissions = [
            ['name' => 'Manage Purchases', 'slug' => 'manage-purchases'],
            ['name' => 'Manage Suppliers', 'slug' => 'manage-suppliers'],
            ['name' => 'Manage Products', 'slug' => 'manage-products'],
            ['name' => 'Manage Customers', 'slug' => 'manage-customers'],
            ['name' => 'Manage Sales', 'slug' => 'manage-sales'],
            ['name' => 'Access Reports', 'slug' => 'access-reports']
        ];

        foreach ($permissions as $permission) {
            $perm = Permission::firstOrCreate($permission);
            $adminRole->permissions()->syncWithoutDetaching([$perm->id]);
        }

        // Assign role to user
        $user->roles()->syncWithoutDetaching([$adminRole->id]);

        echo "Admin user created:\n";
        echo "Email: admin@test.com\n";
        echo "Password: password\n";
    }
}