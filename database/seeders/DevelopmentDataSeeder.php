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
         * Rooms
         */
        $roomA101 = Room::updateOrCreate(
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

        $roomA102 = Room::updateOrCreate(
            [
                'building_id' => $buildingA->id,
                'name' => 'Meeting Room A102',
            ],
            [
                'capacity' => 12,
                'lcd_count' => 1,
                'whiteboard_count' => 1,
                'status' => 'AVAILABLE',
            ]
        );

        $roomB101 = Room::updateOrCreate(
            [
                'building_id' => $buildingB->id,
                'name' => 'Meeting Room B101',
            ],
            [
                'capacity' => 16,
                'lcd_count' => 1,
                'whiteboard_count' => 1,
                'status' => 'AVAILABLE',
            ]
        );

        $roomF101 = Room::updateOrCreate(
            [
                'building_id' => $buildingF->id,
                'name' => 'Meeting Room F101',
            ],
            [
                'capacity' => 25,
                'lcd_count' => 1,
                'whiteboard_count' => 2,
                'status' => 'AVAILABLE',
            ]
        );

        /*
         * Media Training
         */
        $trainingRoomB201 = TrainingRoom::updateOrCreate(
            [
                'building_id' => $buildingB->id,
                'name' => 'Training Room B201',
            ],
            [
                'capacity' => 30,
                'simulation_type' => 'Driving Simulation',
                'simulation_facilities' =>
                    'Driving simulator, projector, sound system',
                'status' => 'AVAILABLE',
            ]
        );

        $trainingRoomF201 = TrainingRoom::updateOrCreate(
            [
                'building_id' => $buildingF->id,
                'name' => 'Training Room F201',
            ],
            [
                'capacity' => 24,
                'simulation_type' => 'Technical Training',
                'simulation_facilities' =>
                    'Training simulator, projector, sound system',
                'status' => 'AVAILABLE',
            ]
        );

        /*
         * Fields
         */
        $fieldMain = Field::updateOrCreate(
            [
                'name' => 'Main Field',
            ],
            [
                'capacity' => 100,
                'status' => 'AVAILABLE',
            ]
        );

        $fieldTraining = Field::updateOrCreate(
            [
                'name' => 'Training Field',
            ],
            [
                'capacity' => 60,
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

        $day4 = Carbon::now()
            ->addDays(5)
            ->setTime(10, 0);

        $day5 = Carbon::now()
            ->addDays(6)
            ->setTime(14, 0);

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
                'room_id' => $roomA101->id,
                'training_room_id' => null,
                'field_id' => null,
                'starts_at' => $day1->copy(),
                'ends_at' => $day1->copy()->addHours(2),
                'total_person' => 10,
                'event_name' => 'Coordinator Approval Test',
                'booker_name' => 'Training Officer',
                'instructor' => 'John Doe',
                'description' =>
                    'Coordinator approval test reservation.',
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
                'room_id' => $roomA101->id,
                'training_room_id' => null,
                'field_id' => null,
                'starts_at' => $day1->copy()->addHour(),
                'ends_at' => $day1->copy()->addHours(3),
                'total_person' => 8,
                'event_name' => 'Coordinator Overlap Test',
                'booker_name' => 'Training Officer',
                'instructor' => 'Jane Doe',
                'description' =>
                    'Coordinator overlap validation test reservation.',
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
                'training_room_id' => $trainingRoomB201->id,
                'field_id' => null,
                'starts_at' => $day2->copy(),
                'ends_at' => $day2->copy()->addHours(2),
                'total_person' => 20,
                'event_name' => 'Media Training Rejection Test',
                'booker_name' => 'Technical Training Group',
                'instructor' => 'Michael Smith',
                'description' =>
                    'Coordinator rejection test reservation.',
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
                'field_id' => $fieldMain->id,
                'starts_at' => $day3->copy(),
                'ends_at' => $day3->copy()->addHours(2),
                'total_person' => 40,
                'event_name' => 'Field Cancellation Test',
                'booker_name' => 'Training Officer',
                'instructor' => 'Robert Johnson',
                'description' =>
                    'Coordinator cancellation test reservation.',
                'status' => 'APPROVED',
                'rejection_reason' => null,
            ]
        );

        /*
         * 5. APPROVED
         *
         * Additional Room test data.
         */
        Reservation::updateOrCreate(
            [
                'reservation_number' => 'RSV-TEST-005',
            ],
            [
                'user_id' => $trainingOfficer->id,
                'room_id' => $roomA102->id,
                'training_room_id' => null,
                'field_id' => null,
                'starts_at' => $day4->copy(),
                'ends_at' => $day4->copy()->addHours(2),
                'total_person' => 6,
                'event_name' => 'Building A Meeting',
                'booker_name' => 'Operations Team',
                'instructor' => null,
                'description' =>
                    'Approved meeting room reservation.',
                'status' => 'APPROVED',
                'rejection_reason' => null,
            ]
        );

        /*
         * 6. PENDING
         *
         * Additional Media Training test data.
         */
        Reservation::updateOrCreate(
            [
                'reservation_number' => 'RSV-TEST-006',
            ],
            [
                'user_id' => $trainingOfficer->id,
                'room_id' => null,
                'training_room_id' => $trainingRoomF201->id,
                'field_id' => null,
                'starts_at' => $day5->copy(),
                'ends_at' => $day5->copy()->addHours(2),
                'total_person' => 18,
                'event_name' => 'Technical Training Session',
                'booker_name' => 'Technical Training Group',
                'instructor' => null,
                'description' =>
                    'Pending technical training reservation.',
                'status' => 'PENDING',
                'rejection_reason' => null,
            ]
        );

        /*
         * 7. APPROVED
         *
         * Additional Field test data.
         */
        Reservation::updateOrCreate(
            [
                'reservation_number' => 'RSV-TEST-007',
            ],
            [
                'user_id' => $trainingOfficer->id,
                'room_id' => null,
                'training_room_id' => null,
                'field_id' => $fieldTraining->id,
                'starts_at' => $day5->copy()->addHours(3),
                'ends_at' => $day5->copy()->addHours(5),
                'total_person' => 30,
                'event_name' => 'Training Field Activity',
                'booker_name' => 'Training Department',
                'instructor' => 'David Wilson',
                'description' =>
                    'Approved training field reservation.',
                'status' => 'APPROVED',
                'rejection_reason' => null,
            ]
        );
    }
}