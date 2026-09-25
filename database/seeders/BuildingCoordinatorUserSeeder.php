<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BuildingCoordinatorUserSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::where('name', 'Building Coordinator')->firstOrFail();

        $buildingA = Building::where('name', 'Building A')->firstOrFail();
        $buildingF = Building::where('name', 'Building F')->firstOrFail();

        $coordinatorA = User::updateOrCreate(
            [
                'username' => 'building_a_coordinator',
            ],
            [
                'role_id' => $role->id,
                'employee_number' => 'BC-A001',
                'name' => 'Building A Coordinator',
                'email' => 'building.a.coordinator@example.com',
                'password' => Hash::make('password'),
            ]
        );

        $coordinatorF = User::updateOrCreate(
            [
                'username' => 'building_f_coordinator',
            ],
            [
                'role_id' => $role->id,
                'employee_number' => 'BC-F001',
                'name' => 'Building F Coordinator',
                'email' => 'building.f.coordinator@example.com',
                'password' => Hash::make('password'),
            ]
        );

        $coordinatorA->buildings()->sync([
            $buildingA->id,
        ]);

        $coordinatorF->buildings()->sync([
            $buildingF->id,
        ]);
    }
}