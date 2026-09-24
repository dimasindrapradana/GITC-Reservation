<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CoordinatorUserSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::where('name', 'Coordinator')->firstOrFail();

        User::updateOrCreate(
            [
                'username' => 'coordinator',
            ],
            [
                'role_id' => $role->id,
                'employee_number' => 'COORD001',
                'name' => 'System Coordinator',
                'email' => 'coordinator@example.com',
                'password' => Hash::make('password'),
            ]
        );
    }
}