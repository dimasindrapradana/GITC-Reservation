<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingController extends Controller
{
    /**
     * Public landing page.
     */
    public function index(): View
    {
        return view('landing.index');
    }

    /**
     * Return approved reservations for the requested month.
     *
     * Supported filters:
     * - year
     * - month
     * - date
     * - search
     * - resource_type
     * - building
     *
     * Only APPROVED reservations are exposed publicly.
     */
    public function reservations(
        Request $request
    ): JsonResponse {
        $validated = $request->validate([
            'year' => [
                'required',
                'integer',
                'min:2020',
                'max:2100',
            ],

            'month' => [
                'required',
                'integer',
                'min:1',
                'max:12',
            ],

            'date' => [
                'nullable',
                'date_format:Y-m-d',
            ],

            'search' => [
                'nullable',
                'string',
                'max:100',
            ],

            'resource_type' => [
                'nullable',
                'string',
                'in:Room,Training Media,Field',
            ],

            'building' => [
                'nullable',
                'string',
                'max:150',
            ],
        ]);

        $timezone = config(
            'app.timezone',
            'Asia/Jakarta'
        );

        /*
         * =====================================================
         * MONTH RANGE
         * =====================================================
         */

        $monthStart = Carbon::createFromDate(
            (int) $validated['year'],
            (int) $validated['month'],
            1,
            $timezone
        )->startOfMonth();

        $monthEnd = $monthStart
            ->copy()
            ->endOfMonth();


        /*
         * =====================================================
         * BASE QUERY
         * =====================================================
         *
         * Only approved reservations whose linked resource
         * and building are still active.
         */

        $query = Reservation::query()
            ->where(
                'status',
                'APPROVED'
            )

            /*
             * Reservation overlaps requested month.
             */
            ->where(
                'starts_at',
                '<',
                $monthEnd
                    ->copy()
                    ->addSecond()
            )
            ->where(
                'ends_at',
                '>=',
                $monthStart
            );


        /*
         * =====================================================
         * RESOURCE + BUILDING SOFT DELETE PROTECTION
         * =====================================================
         */

        $query->where(function (
            Builder $query
        ) {

            /*
             * Room
             */
            $query->whereHas(
                'room',
                function (
                    Builder $query
                ) {

                    $query
                        ->whereNull(
                            'rooms.deleted_at'
                        )
                        ->whereHas(
                            'building',
                            function (
                                Builder $query
                            ) {
                                $query->whereNull(
                                    'buildings.deleted_at'
                                );
                            }
                        );
                }
            )

            /*
             * Training Media
             */
            ->orWhereHas(
                'trainingRoom',
                function (
                    Builder $query
                ) {

                    $query
                        ->whereNull(
                            'training_rooms.deleted_at'
                        )
                        ->whereHas(
                            'building',
                            function (
                                Builder $query
                            ) {
                                $query->whereNull(
                                    'buildings.deleted_at'
                                );
                            }
                        );
                }
            )

            /*
             * Field
             */
            ->orWhereHas(
                'field',
                function (
                    Builder $query
                ) {
                    $query->whereNull(
                        'fields.deleted_at'
                    );
                }
            );
        });


        /*
         * =====================================================
         * DATE FILTER
         * =====================================================
         *
         * Example:
         * ?date=2026-10-05
         *
         * Finds every reservation that overlaps
         * that particular day.
         */

        if (
            !empty(
                $validated['date']
                ?? null
            )
        ) {

            $selectedDate = Carbon::createFromFormat(
                'Y-m-d',
                $validated['date'],
                $timezone
            );

            /*
             * Make sure date belongs to requested month.
             */
            if (
                (int) $selectedDate->year
                    !== (int) $validated['year']
                ||
                (int) $selectedDate->month
                    !== (int) $validated['month']
            ) {
                return response()->json([
                    'year' =>
                        (int) $validated['year'],

                    'month' =>
                        (int) $validated['month'],

                    'date' =>
                        $validated['date'],

                    'reservations' =>
                        [],
                ]);
            }

            $dateStart =
                $selectedDate->copy()
                    ->startOfDay();

            $dateEnd =
                $selectedDate->copy()
                    ->endOfDay();

            $query
                ->where(
                    'starts_at',
                    '<=',
                    $dateEnd
                )
                ->where(
                    'ends_at',
                    '>=',
                    $dateStart
                );
        }


        /*
         * =====================================================
         * RESOURCE TYPE FILTER
         * =====================================================
         */

        $resourceType =
            $validated['resource_type']
            ?? null;

        if ($resourceType === 'Room') {

            $query->whereHas(
                'room',
                function (
                    Builder $query
                ) {
                    $query
                        ->whereNull(
                            'rooms.deleted_at'
                        )
                        ->whereHas(
                            'building',
                            function (
                                Builder $query
                            ) {
                                $query->whereNull(
                                    'buildings.deleted_at'
                                );
                            }
                        );
                }
            );

        } elseif (
            $resourceType === 'Training Media'
        ) {

            $query->whereHas(
                'trainingRoom',
                function (
                    Builder $query
                ) {
                    $query
                        ->whereNull(
                            'training_rooms.deleted_at'
                        )
                        ->whereHas(
                            'building',
                            function (
                                Builder $query
                            ) {
                                $query->whereNull(
                                    'buildings.deleted_at'
                                );
                            }
                        );
                }
            );

        } elseif (
            $resourceType === 'Field'
        ) {

            $query->whereHas(
                'field',
                function (
                    Builder $query
                ) {
                    $query->whereNull(
                        'fields.deleted_at'
                    );
                }
            );
        }


        /*
         * =====================================================
         * BUILDING FILTER
         * =====================================================
         *
         * Applies to Room and Training Media.
         */

        if (
            !empty(
                $validated['building']
                ?? null
            )
        ) {

            $building =
                trim(
                    $validated['building']
                );

            $query->where(function (
                Builder $query
            ) use ($building) {

                $query
                    ->whereHas(
                        'room',
                        function (
                            Builder $query
                        ) use ($building) {

                            $query
                                ->whereNull(
                                    'rooms.deleted_at'
                                )
                                ->whereHas(
                                    'building',
                                    function (
                                        Builder $query
                                    ) use ($building) {

                                        $query
                                            ->whereNull(
                                                'buildings.deleted_at'
                                            )
                                            ->whereRaw(
                                                'LOWER(name) LIKE ?',
                                                [
                                                    '%'
                                                    . strtolower(
                                                        $building
                                                    )
                                                    . '%',
                                                ]
                                            );
                                    }
                                );
                        }
                    )
                    ->orWhereHas(
                        'trainingRoom',
                        function (
                            Builder $query
                        ) use ($building) {

                            $query
                                ->whereNull(
                                    'training_rooms.deleted_at'
                                )
                                ->whereHas(
                                    'building',
                                    function (
                                        Builder $query
                                    ) use ($building) {

                                        $query
                                            ->whereNull(
                                                'buildings.deleted_at'
                                            )
                                            ->whereRaw(
                                                'LOWER(name) LIKE ?',
                                                [
                                                    '%'
                                                    . strtolower(
                                                        $building
                                                    )
                                                    . '%',
                                                ]
                                            );
                                    }
                                );
                        }
                    );
            });
        }


        /*
         * =====================================================
         * SEARCH FILTER
         * =====================================================
         *
         * Searches:
         * - Room name
         * - Training Media name
         * - Field name
         * - Event name
         * - Reservation number
         * - Booker username
         */

        if (
            !empty(
                $validated['search']
                ?? null
            )
        ) {

            $search =
                trim(
                    $validated['search']
                );

            $query->where(function (
                Builder $query
            ) use ($search) {

                $query
                    ->where(
                        'event_name',
                        'LIKE',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'reservation_number',
                        'LIKE',
                        '%' . $search . '%'
                    )

                    /*
                     * Room
                     */
                    ->orWhereHas(
                        'room',
                        function (
                            Builder $query
                        ) use ($search) {

                            $query
                                ->whereNull(
                                    'rooms.deleted_at'
                                )
                                ->where(
                                    'name',
                                    'LIKE',
                                    '%' . $search . '%'
                                );
                        }
                    )

                    /*
                     * Training Media
                     */
                    ->orWhereHas(
                        'trainingRoom',
                        function (
                            Builder $query
                        ) use ($search) {

                            $query
                                ->whereNull(
                                    'training_rooms.deleted_at'
                                )
                                ->where(
                                    'name',
                                    'LIKE',
                                    '%' . $search . '%'
                                );
                        }
                    )

                    /*
                     * Field
                     */
                    ->orWhereHas(
                        'field',
                        function (
                            Builder $query
                        ) use ($search) {

                            $query
                                ->whereNull(
                                    'fields.deleted_at'
                                )
                                ->where(
                                    'name',
                                    'LIKE',
                                    '%' . $search . '%'
                                );
                        }
                    )

                    /*
                     * Booker
                     */
                    ->orWhereHas(
                        'user',
                        function (
                            Builder $query
                        ) use ($search) {

                            $query->where(
                                'username',
                                'LIKE',
                                '%' . $search . '%'
                            );
                        }
                    );
            });
        }


        /*
         * =====================================================
         * LOAD RESERVATIONS
         * =====================================================
         */

        $reservations = $query
            ->with([
                'user',
                'room.building',
                'trainingRoom.building',
                'field',
            ])
            ->orderBy(
                'starts_at'
            )
            ->get();


        /*
         * =====================================================
         * TRANSFORM RESPONSE
         * =====================================================
         */

        $data = $reservations->map(
            function (
                Reservation $reservation
            ) use (
                $timezone
            ) {

                $startsAt = Carbon::parse(
                    $reservation->starts_at
                )->setTimezone(
                    $timezone
                );

                $endsAt = Carbon::parse(
                    $reservation->ends_at
                )->setTimezone(
                    $timezone
                );


                /*
                 * Resolve resource.
                 */

                if (
                    $reservation->room
                ) {

                    $resourceType =
                        'Room';

                    $resourceName =
                        $reservation
                            ->room
                            ->name;

                    $buildingName =
                        $reservation
                            ->room
                            ->building
                            ?->name
                        ?? 'Unknown Building';

                } elseif (
                    $reservation
                        ->trainingRoom
                ) {

                    $resourceType =
                        'Training Media';

                    $resourceName =
                        $reservation
                            ->trainingRoom
                            ->name;

                    $buildingName =
                        $reservation
                            ->trainingRoom
                            ->building
                            ?->name
                        ?? 'Unknown Building';

                } elseif (
                    $reservation->field
                ) {

                    $resourceType =
                        'Field';

                    $resourceName =
                        $reservation
                            ->field
                            ->name;

                    $buildingName =
                        'GITC Facility';

                } else {

                    $resourceType =
                        'Resource';

                    $resourceName =
                        'Unknown Resource';

                    $buildingName =
                        'Unknown Location';
                }


                return [

                    'id' =>
                        $reservation->id,

                    'reservation_number' =>
                        $reservation
                            ->reservation_number,

                    'event_name' =>
                        $reservation
                            ->event_name,

                    'username' =>
                        $reservation->user?->username
                        ?? 'Unknown User',

                    'resource_type' =>
                        $resourceType,

                    'resource_name' =>
                        $resourceName,

                    'building_name' =>
                        $buildingName,

                    'starts_at' =>
                        $startsAt
                            ->format(
                                'Y-m-d H:i'
                            ),

                    'ends_at' =>
                        $endsAt
                            ->format(
                                'Y-m-d H:i'
                            ),

                    'start_date' =>
                        $startsAt
                            ->format(
                                'Y-m-d'
                            ),

                    'end_date' =>
                        $endsAt
                            ->format(
                                'Y-m-d'
                            ),

                    'start_time' =>
                        $startsAt
                            ->format(
                                'H:i'
                            ),

                    'end_time' =>
                        $endsAt
                            ->format(
                                'H:i'
                            ),

                    'status' =>
                        'APPROVED',
                ];
            }
        );


        /*
         * =====================================================
         * JSON RESPONSE
         * =====================================================
         */

        return response()->json([
            'year' =>
                (int) $validated['year'],

            'month' =>
                (int) $validated['month'],

            'date' =>
                $validated['date']
                ?? null,

            'search' =>
                $validated['search']
                ?? null,

            'resource_type' =>
                $validated['resource_type']
                ?? null,

            'building' =>
                $validated['building']
                ?? null,

            'reservations' =>
                $data->values(),
        ]);
    }
}