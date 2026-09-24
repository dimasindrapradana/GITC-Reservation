<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->firstOrFail();

        User::create([
            'role_id' => $adminRole->id,
            'employee_number' => 'ADMIN001',
            'name' => 'System Administrator',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
    }
}