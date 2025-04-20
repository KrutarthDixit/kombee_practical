<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Role Permission
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'delete role']);

        // User Permission
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'delete user']);
    }
}
