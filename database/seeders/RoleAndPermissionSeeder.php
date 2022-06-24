<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        Role::create(['name' => 'user']);
        Role::create(['name' => 'event organiser']);
        Role::create(['name' => 'moderator']);
        Role::create(['name' => 'admin']);
    }
}