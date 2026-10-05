<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\News;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class SecurityDisplayController extends Controller
{
    /**
     * Public Security Display.
     */
    public function index(): View
    {
        $buildings = Building::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return view('display.security', [
            'buildings' => $buildings,
        ]);
    }

    /**
     * Security Display data endpoint.
     *
     * Returns:
     * - all buildings and their resources
     * - today's reservations
     * - tomorrow's reservations
     * - active published news
     */
    public function data(): JsonResponse
    {
        $now = Carbon::now('Asia/Jakarta');

        $todayStart = $now->copy()->startOfDay();
        $todayEnd = $now->copy()->endOfDay();

        $tomorrowStart = $now->copy()->addDay()->startOfDay();
        $tomorrowEnd = $now->copy()->addDay()->endOfDay();

        /*
         * =========================================================
         * BUILDINGS + RESOURCES
         * =========================================================
         */

        $buildings = Building::query()
            ->with([
                'rooms' => function ($query) {
                    $query
                        ->orderBy('name')
                        ->select([
                            'id',
                            'building_id',
                            'name',
                            'capacity',
                            'status',
                        ]);
                },
                'trainingRooms' => function ($query) {
                    $query
                        ->orderBy('name')
                        ->select([
                            'id',
                            'building_id',
                            'name',
                            'capacity',
                            'simulation_type',
                            'status',
                        ]);
                },
            ])
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        /*
         * =========================================================
         * RESERVATIONS
         * =========================================================
         *
         * Security needs operational schedule information, so only
         * PENDING and APPROVED reservations are included here.
         * REJECTED and CANCELLED do not represent active schedule
         * information and therefore are not shown on the public
         * Security Display.
         */

        $reservations = Reservation::query()
            ->with([
                'room.building:id,name',
                'trainingRoom.building:id,name',
                'field:id,name,capacity,status',
                'user:id,name',
            ])
            ->whereIn('status', [
                'PENDING',
                'APPROVED',
            ])
            ->where('starts_at', '<', $tomorrowEnd)
            ->where('ends_at', '>', $todayStart)
            ->where(function ($query) {

                /*
                * ROOM
                *
                * Room harus aktif
                * dan Building-nya juga harus aktif.
                */
                $query->whereHas('room', function ($query) {

                    $query
                        ->whereNull('rooms.deleted_at')
                        ->whereHas('building', function ($query) {

                            $query->whereNull('buildings.deleted_at');

                        });

                })

                /*
                * TRAINING MEDIA
                *
                * Training Media harus aktif
                * dan Building-nya juga harus aktif.
                */
                ->orWhereHas('trainingRoom', function ($query) {

                    $query
                        ->whereNull('training_rooms.deleted_at')
                        ->whereHas('building', function ($query) {

                            $query->whereNull('buildings.deleted_at');

                        });

                })

                /*
                * FIELD
                *
                * Field tidak memiliki Building.
                * Cukup pastikan Field masih aktif.
                */
                ->orWhereHas('field', function ($query) {

                    $query->whereNull('fields.deleted_at');

                });

            })
            ->orderBy('starts_at')
            ->get();

        /*
         * =========================================================
         * RESERVATION FORMATTER
         * =========================================================
         */

        $formatReservation = function (Reservation $reservation): array {
            $resourceType = null;
            $resourceName = null;
            $buildingId = null;
            $buildingName = null;

            if ($reservation->room) {
                $resourceType = 'ROOM';
                $resourceName = $reservation->room->name;

                if ($reservation->room->building) {
                    $buildingId = $reservation->room->building->id;
                    $buildingName = $reservation->room->building->name;
                }
            } elseif ($reservation->trainingRoom) {
                $resourceType = 'TRAINING MEDIA';
                $resourceName = $reservation->trainingRoom->name;

                if ($reservation->trainingRoom->building) {
                    $buildingId = $reservation->trainingRoom->building->id;
                    $buildingName = $reservation->trainingRoom->building->name;
                }
            } elseif ($reservation->field) {
                $resourceType = 'FIELD';
                $resourceName = $reservation->field->name;
            }

            return [
                'id' => $reservation->id,
                'reservation_number' => $reservation->reservation_number,
                'user_id' => $reservation->user_id,
                'event_name' => $reservation->event_name,
                'booker_name' => $reservation->booker_name,
                'user_name' => $reservation->user?->name,
                'instructor' => $reservation->instructor,
                'description' => $reservation->description,
                'starts_at' => $reservation->starts_at?->toIso8601String(),
                'ends_at' => $reservation->ends_at?->toIso8601String(),
                'total_person' => $reservation->total_person,
                'status' => $reservation->status,
                'building' => [
                    'id' => $buildingId,
                    'name' => $buildingName,
                ],
                'resource' => [
                    'type' => $resourceType,
                    'name' => $resourceName,
                ],
                'location_name' => $resourceName,
            ];
        };

        $todayReservations = $reservations
            ->filter(function (Reservation $reservation) use ($todayStart, $todayEnd) {
                return $reservation->starts_at < $todayEnd
                    && $reservation->ends_at > $todayStart;
            })
            ->map($formatReservation)
            ->values();

        $tomorrowReservations = $reservations
            ->filter(function (Reservation $reservation) use ($tomorrowStart, $tomorrowEnd) {
                return $reservation->starts_at < $tomorrowEnd
                    && $reservation->ends_at > $tomorrowStart;
            })
            ->map($formatReservation)
            ->values();

        /*
         * =========================================================
         * ACTIVE NEWS
         * =========================================================
         */

        $news = News::query()
            ->with([
                'images' => function ($query) {
                    $query->orderBy('sort_order');
                },
            ])
            ->where('status', 'PUBLISHED')
            ->where('starts_at', '<=', $now)
            ->where('ends_at', '>=', $now)
            ->orderByDesc('created_at')
            ->get();

        $newsData = $news
            ->map(function (News $item): array {
                $images = $item->images
                    ->map(function ($image): array {
                        return [
                            'id' => $image->id,
                            'file' => $image->file,
                            'url' => Storage::disk('public')->url($image->file),
                            'sort_order' => $image->sort_order,
                        ];
                    })
                    ->values();

                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'content' => $item->content,
                    'starts_at' => $item->starts_at?->toIso8601String(),
                    'ends_at' => $item->ends_at?->toIso8601String(),
                    'status' => $item->status,
                    'created_at' => $item->created_at?->toIso8601String(),
                    'images' => $images,
                    'image_url' => $images->first()['url'] ?? null,
                ];
            })
            ->values();

        /*
         * =========================================================
         * BUILDING RESPONSE
         * =========================================================
         */

        $buildingData = $buildings
            ->map(function ($building): array {
                return [
                    'id' => $building->id,
                    'name' => $building->name,
                    'rooms' => $building->rooms
                        ->map(function ($room): array {
                            return [
                                'id' => $room->id,
                                'name' => $room->name,
                                'capacity' => $room->capacity,
                                'status' => $room->status,
                            ];
                        })
                        ->values(),
                    'training_media' => $building->trainingRooms
                        ->map(function ($trainingRoom): array {
                            return [
                                'id' => $trainingRoom->id,
                                'name' => $trainingRoom->name,
                                'capacity' => $trainingRoom->capacity,
                                'simulation_type' => $trainingRoom->simulation_type,
                                'status' => $trainingRoom->status,
                            ];
                        })
                        ->values(),
                ];
            })
            ->values();

        return response()->json([
            'current_time' => $now->toIso8601String(),

            'today' => [
                'date' => $todayStart->toDateString(),
                'reservations' => $todayReservations,
            ],

            'tomorrow' => [
                'date' => $tomorrowStart->toDateString(),
                'reservations' => $tomorrowReservations,
            ],

            'buildings' => $buildingData,

            'news' => $newsData,

            'counts' => [
                'today' => $todayReservations->count(),
                'tomorrow' => $tomorrowReservations->count(),
                'today_live' => $todayReservations->filter(function (array $reservation) use ($now) {
                    $start = Carbon::parse($reservation['starts_at']);
                    $end = Carbon::parse($reservation['ends_at']);

                    return $start <= $now && $end >= $now;
                })->count(),
            ],
        ]);
    }
}
