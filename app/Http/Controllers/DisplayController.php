<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\News;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class DisplayController extends Controller
{
    /**
     * Display Smart TV page.
     *
     * Example:
     * /display/building_A
     */
    public function index(string $buildingName): View
    {
        $building = $this->findBuilding($buildingName);

        return view('display.index', [
            'building' => $building,
            'buildingName' => $buildingName,
        ]);
    }

    /**
     * Display Smart TV data API.
     *
     * Example:
     * /display/building_A/data
     */
    public function data(string $buildingName): JsonResponse
    {
        $building = $this->findBuilding($buildingName);

        $building->load([
            'rooms',
            'trainingRooms',
        ]);

        $now = Carbon::now();

        /*
         * =========================
         * DATE RANGE
         * =========================
         */

        $todayStart = $now->copy()->startOfDay();

        $todayEnd = $now->copy()->endOfDay();

        $tomorrowStart = $now
            ->copy()
            ->addDay()
            ->startOfDay();

        $tomorrowEnd = $now
            ->copy()
            ->addDay()
            ->endOfDay();

        /*
         * =========================
         * RESOURCE IDS
         * =========================
         */

        $roomIds = $building
            ->rooms
            ->pluck('id');

        $trainingRoomIds = $building
            ->trainingRooms
            ->pluck('id');

        /*
         * =========================
         * TODAY'S RESERVATIONS
         * =========================
         */

        $reservations = Reservation::query()
            ->with([
                'room',
                'trainingRoom',
                'field',
            ])
            ->where(
                'status',
                '!=',
                'REJECTED'
            )
            ->where(function ($query) use (
                $roomIds,
                $trainingRoomIds
            ) {
                $query
                    ->whereIn(
                        'room_id',
                        $roomIds
                    )
                    ->orWhereIn(
                        'training_room_id',
                        $trainingRoomIds
                    );
            })
            ->where(
                'starts_at',
                '<',
                $todayEnd
            )
            ->where(
                'ends_at',
                '>',
                $todayStart
            )
            ->orderBy('starts_at')
            ->get();

        /*
         * =========================
         * TOMORROW'S RESERVATIONS
         * =========================
         */

        $tomorrowReservations = Reservation::query()
            ->with([
                'room',
                'trainingRoom',
                'field',
            ])
            ->where(
                'status',
                '!=',
                'REJECTED'
            )
            ->where(function ($query) use (
                $roomIds,
                $trainingRoomIds
            ) {
                $query
                    ->whereIn(
                        'room_id',
                        $roomIds
                    )
                    ->orWhereIn(
                        'training_room_id',
                        $trainingRoomIds
                    );
            })
            ->where(
                'starts_at',
                '<',
                $tomorrowEnd
            )
            ->where(
                'ends_at',
                '>',
                $tomorrowStart
            )
            ->orderBy('starts_at')
            ->get();

        /*
         * =========================
         * FORMAT RESERVATION
         * =========================
         */

        $formatReservation = function (
            Reservation $reservation
        ) {
            $resourceType = null;

            $resourceName = null;

            /*
             * ROOM
             */
            if ($reservation->room) {
                $resourceType = 'ROOM';

                $resourceName =
                    $reservation
                        ->room
                        ->name;
            }

            /*
             * TRAINING MEDIA
             */
            elseif ($reservation->trainingRoom) {
                $resourceType =
                    'TRAINING MEDIA';

                $resourceName =
                    $reservation
                        ->trainingRoom
                        ->name;
            }

            /*
             * FIELD
             */
            elseif ($reservation->field) {
                $resourceType = 'FIELD';

                $resourceName =
                    $reservation
                        ->field
                        ->name;
            }

            return [
                'id' =>
                    $reservation
                        ->id,

                'reservation_number' =>
                    $reservation
                        ->reservation_number,

                'event_name' =>
                    $reservation
                        ->event_name,

                'booker_name' =>
                    $reservation
                        ->booker_name,

                'instructor' =>
                    $reservation
                        ->instructor,

                'description' =>
                    $reservation
                        ->description,

                'starts_at' =>
                    $reservation
                        ->starts_at
                        ?->toIso8601String(),

                'ends_at' =>
                    $reservation
                        ->ends_at
                        ?->toIso8601String(),

                'total_person' =>
                    $reservation
                        ->total_person,

                'status' =>
                    $reservation
                        ->status,

                'resource' => [
                    'type' =>
                        $resourceType,

                    'name' =>
                        $resourceName,
                ],
            ];
        };

        /*
         * =========================
         * FORMAT TODAY
         * =========================
         */

        $todayData = $reservations
            ->map($formatReservation)
            ->values();

        /*
         * =========================
         * FORMAT TOMORROW
         * =========================
         */

        $tomorrowData = $tomorrowReservations
            ->map($formatReservation)
            ->values();

        /*
         * =========================
         * CURRENT EVENT
         * =========================
         */

        $currentEvent = $reservations
            ->first(function ($reservation) use ($now) {
                return $reservation->starts_at <= $now
                    && $reservation->ends_at >= $now;
            });

        /*
         * =========================
         * NEXT EVENT
         * =========================
         */

        $nextEvent = $reservations
            ->first(function ($reservation) use ($now) {
                return $reservation->starts_at > $now;
            });

        /*
         * =========================
         * ACTIVE NEWS
         * =========================
         *
         * Hanya News yang:
         *
         * status    = PUBLISHED
         * starts_at <= sekarang
         * ends_at   >= sekarang
         */

        $news = News::query()
            ->with([
                'images' => function ($query) {
                    $query->orderBy('sort_order');
                },
            ])
            ->where(
                'status',
                'PUBLISHED'
            )
            ->where(
                'starts_at',
                '<=',
                $now
            )
            ->where(
                'ends_at',
                '>=',
                $now
            )
            ->orderByDesc('created_at')
            ->get();

        /*
         * =========================
         * FORMAT NEWS
         * =========================
         */

        $newsData = $news
            ->map(function (News $item) {

                $images = $item
                    ->images
                    ->map(function ($image) {

                        return [
                            'id' =>
                                $image
                                    ->id,

                            'file' =>
                                $image
                                    ->file,

                            'url' =>
                                Storage
                                    ::disk(
                                        'public'
                                    )
                                    ->url(
                                        $image
                                            ->file
                                    ),

                            'sort_order' =>
                                $image
                                    ->sort_order,
                        ];
                    })
                    ->values();

                return [
                    'id' =>
                        $item
                            ->id,

                    'title' =>
                        $item
                            ->title,

                    'content' =>
                        $item
                            ->content,

                    'starts_at' =>
                        $item
                            ->starts_at
                            ?->toIso8601String(),

                    'ends_at' =>
                        $item
                            ->ends_at
                            ?->toIso8601String(),

                    'status' =>
                        $item
                            ->status,

                    'images' =>
                        $images,

                    'image_url' =>
                        $images
                            ->first()['url']
                            ?? null,
                ];
            })
            ->values();

        /*
         * =========================
         * RESPONSE
         * =========================
         */

        return response()->json([

            /*
             * =====================
             * BUILDING
             * =====================
             */

            'building' => [
                'id' =>
                    $building
                        ->id,

                'name' =>
                    $building
                        ->name,

                'display_name' =>
                    $building
                        ->name,
            ],

            /*
             * =====================
             * CURRENT TIME
             * =====================
             */

            'current_time' =>
                $now
                    ->toIso8601String(),

            /*
             * =====================
             * CURRENT EVENT
             * =====================
             */

            'current_event' =>
                $currentEvent
                    ? $formatReservation(
                        $currentEvent
                    )
                    : null,

            /*
             * =====================
             * NEXT EVENT
             * =====================
             */

            'next_event' =>
                $nextEvent
                    ? $formatReservation(
                        $nextEvent
                    )
                    : null,

            /*
             * =====================
             * TODAY
             * =====================
             */

            'reservations' =>
                $todayData,

            'today_schedule' =>
                $todayData,

            /*
             * =====================
             * TOMORROW
             * =====================
             */

            'tomorrow_reservations' =>
                $tomorrowData,

            'upcoming_class' =>
                $tomorrowData,

            /*
             * =====================
             * ROOMS
             * =====================
             */

            'rooms' =>
                $building
                    ->rooms
                    ->map(function ($room) {

                        return [
                            'id' =>
                                $room
                                    ->id,

                            'name' =>
                                $room
                                    ->name,

                            'capacity' =>
                                $room
                                    ->capacity,

                            'lcd_count' =>
                                $room
                                    ->lcd_count,

                            'whiteboard_count' =>
                                $room
                                    ->whiteboard_count,

                            'status' =>
                                $room
                                    ->status,
                        ];
                    })
                    ->values(),

            /*
             * =====================
             * TRAINING MEDIA
             * =====================
             */

            'training_media' =>
                $building
                    ->trainingRooms
                    ->map(function (
                        $trainingRoom
                    ) {

                        return [
                            'id' =>
                                $trainingRoom
                                    ->id,

                            'name' =>
                                $trainingRoom
                                    ->name,

                            'capacity' =>
                                $trainingRoom
                                    ->capacity,

                            'simulation_type' =>
                                $trainingRoom
                                    ->simulation_type,

                            'simulation_facilities' =>
                                $trainingRoom
                                    ->simulation_facilities,

                            'status' =>
                                $trainingRoom
                                    ->status,
                        ];
                    })
                    ->values(),

            /*
             * =====================
             * NEWS
             * =====================
             */

            'news' =>
                $newsData,
        ]);
    }

    /**
     * Display upcoming schedule page.
     *
     * Example:
     * /display/building_A/upcoming
     */
    public function upcoming(
        string $buildingName
    ): View {
        $building =
            $this->findBuilding(
                $buildingName
            );

        return view('display.upcoming', [
            'building' =>
                $building,

            'buildingName' =>
                $buildingName,
        ]);
    }

    /**
     * Display upcoming schedule data API.
     *
     * Returns the next 7 days
     * starting from tomorrow.
     *
     * Example:
     *
     * Today:
     * 30 September
     *
     * Result:
     * 01 October
     * 02 October
     * 03 October
     * 04 October
     * 05 October
     * 06 October
     * 07 October
     */
    public function upcomingData(
        string $buildingName
    ): JsonResponse {
        $building =
            $this->findBuilding(
                $buildingName
            );

        $building->load([
            'rooms',
            'trainingRooms',
        ]);

        $now =
            Carbon::now();

        /*
         * =========================
         * NEXT 7 DAYS
         * =========================
         *
         * Start:
         * tomorrow 00:00
         *
         * End:
         * seven days from today,
         * end of day.
         */

        $rangeStart =
            $now
                ->copy()
                ->addDay()
                ->startOfDay();

        $rangeEnd =
            $now
                ->copy()
                ->addDays(7)
                ->endOfDay();

        /*
         * =========================
         * RESOURCE IDS
         * =========================
         */

        $roomIds =
            $building
                ->rooms
                ->pluck('id');

        $trainingRoomIds =
            $building
                ->trainingRooms
                ->pluck('id');

        /*
         * =========================
         * RESERVATIONS
         * =========================
         */

        $reservations = Reservation::query()
            ->with([
                'room',
                'trainingRoom',
                'field',
            ])
            ->where(
                'status',
                '!=',
                'REJECTED'
            )
            ->where(function ($query) use (
                $roomIds,
                $trainingRoomIds
            ) {
                $query
                    ->whereIn(
                        'room_id',
                        $roomIds
                    )
                    ->orWhereIn(
                        'training_room_id',
                        $trainingRoomIds
                    );
            })
            ->where(
                'starts_at',
                '<',
                $rangeEnd
            )
            ->where(
                'ends_at',
                '>',
                $rangeStart
            )
            ->orderBy(
                'starts_at'
            )
            ->get();

        /*
         * =========================
         * FORMAT RESERVATION
         * =========================
         */

        $formatReservation =
            function (
                Reservation $reservation
            ) {

                $resourceType =
                    null;

                $resourceName =
                    null;

                /*
                 * ROOM
                 */

                if (
                    $reservation->room
                ) {

                    $resourceType =
                        'ROOM';

                    $resourceName =
                        $reservation
                            ->room
                            ->name;

                }

                /*
                 * TRAINING MEDIA
                 */

                elseif (
                    $reservation
                        ->trainingRoom
                ) {

                    $resourceType =
                        'TRAINING MEDIA';

                    $resourceName =
                        $reservation
                            ->trainingRoom
                            ->name;

                }

                /*
                 * FIELD
                 */

                elseif (
                    $reservation->field
                ) {

                    $resourceType =
                        'FIELD';

                    $resourceName =
                        $reservation
                            ->field
                            ->name;

                }

                return [
                    'id' =>
                        $reservation
                            ->id,

                    'reservation_number' =>
                        $reservation
                            ->reservation_number,

                    'event_name' =>
                        $reservation
                            ->event_name,

                    'booker_name' =>
                        $reservation
                            ->booker_name,

                    'instructor' =>
                        $reservation
                            ->instructor,

                    'description' =>
                        $reservation
                            ->description,

                    'starts_at' =>
                        $reservation
                            ->starts_at
                            ?->toIso8601String(),

                    'ends_at' =>
                        $reservation
                            ->ends_at
                            ?->toIso8601String(),

                    'total_person' =>
                        $reservation
                            ->total_person,

                    'status' =>
                        $reservation
                            ->status,

                    'resource' => [

                        'type' =>
                            $resourceType,

                        'name' =>
                            $resourceName,
                    ],
                ];
            };

        /*
         * =========================
         * GROUP BY DATE
         * =========================
         */

        $schedule =
            $reservations
                ->map(
                    $formatReservation
                )
                ->groupBy(
                    function (
                        $reservation
                    ) {

                        return Carbon::parse(
                            $reservation[
                                'starts_at'
                            ]
                        )
                            ->timezone(
                                'Asia/Jakarta'
                            )
                            ->format(
                                'Y-m-d'
                            );
                    }
                )
                ->map(
                    function (
                        $items,
                        $date
                    ) {

                        $dateCarbon =
                            Carbon::parse(
                                $date,
                                'Asia/Jakarta'
                            );

                        return [
                            'date' =>
                                $date,

                            'formatted_date' =>
                                $dateCarbon
                                    ->translatedFormat(
                                        'l, d F Y'
                                    ),

                            'reservations' =>
                                $items
                                    ->values(),
                        ];
                    }
                )
                ->values();

        /*
         * =========================
         * RESPONSE
         * =========================
         */

        return response()->json([

            /*
             * =====================
             * BUILDING
             * =====================
             */

            'building' => [

                'id' =>
                    $building
                        ->id,

                'name' =>
                    $building
                        ->name,

                'display_name' =>
                    $building
                        ->name,
            ],

            /*
             * =====================
             * CURRENT TIME
             * =====================
             */

            'current_time' =>
                $now
                    ->toIso8601String(),

            /*
             * =====================
             * RANGE
             * =====================
             */

            'range_start' =>
                $rangeStart
                    ->toIso8601String(),

            'range_end' =>
                $rangeEnd
                    ->toIso8601String(),

            /*
             * =====================
             * SCHEDULE
             * =====================
             */

            'schedule' =>
                $schedule,
        ]);
    }

    /**
     * Find building from display URL.
     *
     * Examples:
     *
     * building_A
     * building_B
     * building_F
     *
     * become:
     *
     * Building A
     * Building B
     * Building F
     */
    private function findBuilding(
        string $buildingName
    ): Building {
        /*
         * Convert underscore to space.
         *
         * building_A
         *
         * ->
         *
         * building A
         */

        $normalizedName =
            str_replace(
                '_',
                ' ',
                $buildingName
            );

        /*
         * Trim unnecessary spaces.
         */

        $normalizedName =
            trim(
                $normalizedName
            );

        /*
         * Case-insensitive search.
         *
         * building A
         * BUILDING A
         * Building A
         *
         * will all find:
         *
         * Building A
         */

        $building = Building::query()
            ->whereRaw(
                'LOWER(name) = ?',
                [
                    strtolower(
                        $normalizedName
                    ),
                ]
            )
            ->first();

        /*
         * Building not found.
         */

        if (!$building) {

            abort(
                404,
                'Building not found.'
            );
        }

        return $building;
    }
}