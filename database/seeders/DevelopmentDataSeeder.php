<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Field;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\TrainingRoom;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DevelopmentDataSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Buildings
         */
        $buildingA = Building::firstOrCreate([
            'name' => 'Building A',
        ]);

        $buildingB = Building::firstOrCreate([
            'name' => 'Building B',
        ]);

        $buildingF = Building::firstOrCreate([
            'name' => 'Building F',
        ]);

        /*
         * Resources
         */
        $room = Room::updateOrCreate(
            [
                'building_id' => $buildingA->id,
                'name' => 'Meeting Room A101',
            ],
            [
                'capacity' => 20,
                'lcd_count' => 1,
                'whiteboard_count' => 1,
                'status' => 'AVAILABLE',
            ]
        );

        $trainingRoom = TrainingRoom::updateOrCreate(
            [
                'building_id' => $buildingB->id,
                'name' => 'Training Room B201',
            ],
            [
                'capacity' => 30,
                'simulation_type' => 'Driving Simulation',
                'simulation_facilities' => 'Driving simulator, projector, sound system',
                'status' => 'AVAILABLE',
            ]
        );

        $field = Field::updateOrCreate(
            [
                'name' => 'Main Field',
            ],
            [
                'capacity' => 100,
                'status' => 'AVAILABLE',
            ]
        );

        /*
         * Users
         */
        $trainingOfficer = User::where(
            'username',
            'training_officer'
        )->firstOrFail();

        /*
         * Schedule
         */
        $day1 = Carbon::now()
            ->addDays(2)
            ->setTime(9, 0);

        $day2 = Carbon::now()
            ->addDays(3)
            ->setTime(9, 0);

        $day3 = Carbon::now()
            ->addDays(4)
            ->setTime(13, 0);

        /*
         * 1. PENDING
         *
         * Intended for successful approval test.
         */
        Reservation::updateOrCreate(
            [
                'reservation_number' => 'RSV-TEST-001',
            ],
            [
                'user_id' => $trainingOfficer->id,
                'room_id' => $room->id,
                'training_room_id' => null,
                'field_id' => null,
                'starts_at' => $day1->copy(),
                'ends_at' => $day1->copy()->addHours(2),
                'instructor' => 'John Doe',
                'description' => 'Coordinator approval test reservation.',
                'status' => 'PENDING',
                'rejection_reason' => null,
            ]
        );

        /*
         * 2. PENDING
         *
         * Intentionally overlaps RSV-TEST-001.
         * Approving this reservation should fail because
         * the selected time slot is already occupied.
         */
        Reservation::updateOrCreate(
            [
                'reservation_number' => 'RSV-TEST-002',
            ],
            [
                'user_id' => $trainingOfficer->id,
                'room_id' => $room->id,
                'training_room_id' => null,
                'field_id' => null,
                'starts_at' => $day1->copy()->addHour(),
                'ends_at' => $day1->copy()->addHours(3),
                'instructor' => 'Jane Doe',
                'description' => 'Coordinator overlap validation test reservation.',
                'status' => 'PENDING',
                'rejection_reason' => null,
            ]
        );

        /*
         * 3. PENDING
         *
         * Intended for rejection test.
         */
        Reservation::updateOrCreate(
            [
                'reservation_number' => 'RSV-TEST-003',
            ],
            [
                'user_id' => $trainingOfficer->id,
                'room_id' => null,
                'training_room_id' => $trainingRoom->id,
                'field_id' => null,
                'starts_at' => $day2->copy(),
                'ends_at' => $day2->copy()->addHours(2),
                'instructor' => 'Michael Smith',
                'description' => 'Coordinator rejection test reservation.',
                'status' => 'PENDING',
                'rejection_reason' => null,
            ]
        );

        /*
         * 4. APPROVED
         *
         * Intended for cancellation test.
         */
        Reservation::updateOrCreate(
            [
                'reservation_number' => 'RSV-TEST-004',
            ],
            [
                'user_id' => $trainingOfficer->id,
                'room_id' => null,
                'training_room_id' => null,
                'field_id' => $field->id,
                'starts_at' => $day3->copy(),
                'ends_at' => $day3->copy()->addHours(2),
                'instructor' => 'Robert Johnson',
                'description' => 'Coordinator cancellation test reservation.',
                'status' => 'APPROVED',
                'rejection_reason' => null,
            ]
        );
    }
}