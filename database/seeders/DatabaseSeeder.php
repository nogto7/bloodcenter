<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --------------------------
        // Permissions үүсгэх
        // --------------------------
        $permissions = ['manage news', 'manage files', 'view all data'];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // --------------------------
        // Roles үүсгэх
        // --------------------------
        $roles = ['super-admin', 'admin', 'editor', 'publisher'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // --------------------------
        // Permissions-г Roles-д өгөх
        // --------------------------
        $super = Role::where('name', 'super-admin')->first();
        $admin = Role::where('name', 'admin')->first();
        $editor = Role::where('name', 'editor')->first();
        $publisher = Role::where('name', 'publisher')->first();

        $super->syncPermissions(Permission::all());
        $admin->syncPermissions(Permission::whereIn('name', ['manage news', 'manage files'])->get());
        $editor->syncPermissions(Permission::whereIn('name', ['manage news'])->get());
        $publisher->syncPermissions(Permission::whereIn('name', ['manage news'])->get());

        // --------------------------
        // User үүсгэх
        // --------------------------
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // заавал password хийж өгөх
        ]);
    }
}
