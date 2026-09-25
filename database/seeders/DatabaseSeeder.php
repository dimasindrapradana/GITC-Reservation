<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AdminUserSeeder::class,
            CoordinatorUserSeeder::class,
            BuildingCoordinatorUserSeeder::class,
            TrainingOfficerUserSeeder::class,
            DevelopmentDataSeeder::class,
        ]);
    }
}