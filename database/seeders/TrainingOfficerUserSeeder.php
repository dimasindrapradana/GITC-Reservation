<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrainingOfficerUserSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::where('name', 'Training Officer')->firstOrFail();

        User::updateOrCreate(
            [
                'username' => 'training_officer',
            ],
            [
                'role_id' => $role->id,
                'employee_number' => 'TRAIN001',
                'name' => 'System Training Officer',
                'email' => 'training.officer@example.com',
                'password' => Hash::make('password'),
            ]
        );
    }
}