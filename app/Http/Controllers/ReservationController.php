<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Building;
use App\Models\Field;
use App\Models\Notification;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\TrainingRoom;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function myReservations(Request $request): View
    {
        $reservations = Reservation::query()
            ->with([
                'room.building',
                'trainingRoom.building',
                'field',
            ])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('reservations.my-index', [
            'reservations' => $reservations,
        ]);
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')
            ->trim()
            ->toString();

        $building = $request->string('building')
            ->toString();

        $status = $request->string('status')
            ->toString();

        $resourceType = $request->string('resource_type')
            ->toString();

        $sort = $request->string('sort')
            ->toString();

        $dateFrom = $request->string('date_from')
            ->trim()
            ->toString();

        $dateTo = $request->string('date_to')
            ->trim()
            ->toString();

        $isFieldCoordinator = $this->isFieldCoordinator();

        if ($isFieldCoordinator) {
            $resourceType = 'field';
        }

        $allowedSorts = [
            'created_desc',
            'created_asc',
        ];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_desc';
        }

        $reservations = Reservation::query()
            ->with([
                'user',
                'room.building',
                'trainingRoom.building',
                'field',
            ])

            /*
            |--------------------------------------------------------------------------
            | Field Coordinator restriction
            |--------------------------------------------------------------------------
            */

            ->when(
                $isFieldCoordinator,
                function ($query) {
                    $query->whereNotNull('field_id');
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Search
            |--------------------------------------------------------------------------
            */

            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where(
                            'reservation_number',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'event_name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'booker_name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'instructor',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'description',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query
                                ->where(
                                    'name',
                                    'like',
                                    '%' . $search . '%'
                                )
                                ->orWhere(
                                    'employee_number',
                                    'like',
                                    '%' . $search . '%'
                                );
                        })
                        ->orWhereHas('room', function ($query) use ($search) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        })
                        ->orWhereHas('trainingRoom', function ($query) use ($search) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        })
                        ->orWhereHas('field', function ($query) use ($search) {
                            $query->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        });
                });
            })

            /*
            |--------------------------------------------------------------------------
            | Building
            |--------------------------------------------------------------------------
            */

            ->when(
                !$isFieldCoordinator && $building !== '',
                function ($query) use ($building) {
                    $query->where(function ($query) use ($building) {
                        $query
                            ->whereHas('room', function ($query) use ($building) {
                                $query->where(
                                    'building_id',
                                    $building
                                );
                            })
                            ->orWhereHas('trainingRoom', function ($query) use ($building) {
                                $query->where(
                                    'building_id',
                                    $building
                                );
                            });
                    });
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            ->when($status !== '', function ($query) use ($status) {
                $query->where(
                    'status',
                    $status
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Resource Type
            |--------------------------------------------------------------------------
            */

            ->when(
                $resourceType === 'room',
                function ($query) {
                    $query->whereNotNull('room_id');
                }
            )

            ->when(
                $resourceType === 'training_room',
                function ($query) {
                    $query->whereNotNull('training_room_id');
                }
            )

            ->when(
                $resourceType === 'field',
                function ($query) {
                    $query->whereNotNull('field_id');
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Date From
            |
            | Reservation must end on or after Date From.
            |--------------------------------------------------------------------------
            */

            ->when(
                $dateFrom !== '',
                function ($query) use ($dateFrom) {
                    try {
                        $from = Carbon::createFromFormat(
                            'Y-m-d',
                            $dateFrom,
                            config('app.timezone')
                        )->startOfDay();

                        $query->where(
                            'ends_at',
                            '>=',
                            $from
                        );
                    } catch (\Throwable $e) {
                        // Ignore invalid date input.
                    }
                }
            )

            /*
            |--------------------------------------------------------------------------
            | Date To
            |
            | Reservation must start before the end of Date To.
            |--------------------------------------------------------------------------
            */

            ->when(
                $dateTo !== '',
                function ($query) use ($dateTo) {
                    try {
                        $to = Carbon::createFromFormat(
                            'Y-m-d',
                            $dateTo,
                            config('app.timezone')
                        )->endOfDay();

                        $query->where(
                            'starts_at',
                            '<=',
                            $to
                        );
                    } catch (\Throwable $e) {
                        // Ignore invalid date input.
                    }
                }
            );

        /*
        |--------------------------------------------------------------------------
        | Sort
        |--------------------------------------------------------------------------
        */

        switch ($sort) {
            case 'created_asc':
                $reservations->orderBy(
                    'created_at',
                    'asc'
                );
                break;

            case 'created_desc':
            default:
                $reservations->orderBy(
                    'created_at',
                    'desc'
                );
                break;
        }

        $reservations = $reservations
            ->paginate(10)
            ->withQueryString();

        $buildings = Building::query()
            ->orderBy('name')
            ->get();

        return view('reservations.index', [
            'reservations' => $reservations,
            'buildings' => $buildings,
            'search' => $search,
            'building' => $building,
            'status' => $status,
            'resourceType' => $resourceType,
            'sort' => $sort,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }

    public function create(): View
    {
        $users = User::query()
            ->orderBy('name')
            ->get();

        $isFieldCoordinator = $this->isFieldCoordinator();

        $rooms = collect();

        $trainingRooms = collect();

        $fields = Field::query()
            ->where('status', 'AVAILABLE')
            ->orderBy('name')
            ->get();

        if (!$isFieldCoordinator) {
            $rooms = Room::query()
                ->with('building')
                ->where('status', 'AVAILABLE')
                ->orderBy('building_id')
                ->orderBy('name')
                ->get();

            $trainingRooms = TrainingRoom::query()
                ->with('building')
                ->where('status', 'AVAILABLE')
                ->orderBy('building_id')
                ->orderBy('name')
                ->get();
        }

        return view('reservations.create', [
            'users' => $users,
            'rooms' => $rooms,
            'trainingRooms' => $trainingRooms,
            'fields' => $fields,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'resource_type' => [
                'required',
                Rule::in([
                    'room',
                    'training_room',
                    'field',
                ]),
            ],

            'resource_id' => [
                'required',
                'integer',
            ],

            'starts_at' => [
                'required',
                'date',
            ],

            'ends_at' => [
                'required',
                'date',
                'after:starts_at',
            ],

            'total_person' => [
                'required',
                'integer',
                'min:1',
            ],

            'event_name' => [
                'required',
                'string',
                'max:255',
            ],

            'booker_name' => [
                'required',
                'string',
                'max:255',
            ],

            'instructor' => [
                'nullable',
                'string',
            ],

            'description' => [
                'required',
                'string',
            ],
        ]);

        $resourceType = $validated['resource_type'];

        if (
            $this->isFieldCoordinator()
            && $resourceType !== 'field'
        ) {
            abort(
                403,
                'Field Coordinators can only manage field reservations.'
            );
        }

        $startsAt = Carbon::parse(
            $validated['starts_at']
        )->setTimezone(
            config('app.timezone')
        );

        $endsAt = Carbon::parse(
            $validated['ends_at']
        )->setTimezone(
            config('app.timezone')
        );

        if (!$startsAt->lt($endsAt)) {
            return back()
                ->withInput()
                ->withErrors([
                    'ends_at' =>
                        'The end date and time must be after the start date and time.',
                ]);
        }

        $resourceId = (int) $validated['resource_id'];

        $resource = match ($resourceType) {
            'room' => Room::find($resourceId),
            'training_room' => TrainingRoom::find($resourceId),
            'field' => Field::find($resourceId),
        };

        if (!$resource) {
            return back()
                ->withInput()
                ->withErrors([
                    'resource_id' =>
                        'The selected resource could not be found.',
                ]);
        }

        if ($resource->status !== 'AVAILABLE') {
            return back()
                ->withInput()
                ->withErrors([
                    'resource_id' =>
                        'The selected resource is currently under maintenance and cannot be reserved.',
                ]);
        }

        $resourceColumn = match ($resourceType) {
            'room' => 'room_id',
            'training_room' => 'training_room_id',
            'field' => 'field_id',
        };

        $hasOverlap = Reservation::query()
            ->where(
                $resourceColumn,
                $resourceId
            )
            ->whereIn('status', [
                'PENDING',
                'APPROVED',
            ])
            ->where(function ($query) use (
                $startsAt,
                $endsAt
            ) {
                $query
                    ->where(
                        'starts_at',
                        '<',
                        $endsAt
                    )
                    ->where(
                        'ends_at',
                        '>',
                        $startsAt
                    );
            })
            ->exists();

        if ($hasOverlap) {
            return back()
                ->withInput()
                ->withErrors([
                    'starts_at' =>
                        'The selected resource is already reserved during the requested time.',
                ]);
        }

        $reservation = DB::transaction(
            function () use (
                $validated,
                $resourceType,
                $resourceId,
                $startsAt,
                $endsAt
            ) {
                $reservationNumber =
                    $this->generateReservationNumber();

                $reservation = Reservation::create([
                    'reservation_number' =>
                        $reservationNumber,

                    'user_id' =>
                        $validated['user_id'],

                    'room_id' =>
                        $resourceType === 'room'
                            ? $resourceId
                            : null,

                    'training_room_id' =>
                        $resourceType === 'training_room'
                            ? $resourceId
                            : null,

                    'field_id' =>
                        $resourceType === 'field'
                            ? $resourceId
                            : null,

                    'starts_at' =>
                        $startsAt,

                    'ends_at' =>
                        $endsAt,

                    'total_person' =>
                        $validated['total_person'],

                    'event_name' =>
                        $validated['event_name'],

                    'booker_name' =>
                        $validated['booker_name'],

                    'instructor' =>
                        $validated['instructor'] ?? null,

                    'description' =>
                        $validated['description'],

                    'status' =>
                        'PENDING',

                    'rejection_reason' =>
                        null,
                ]);

                AuditLog::create([
                    'user_id' =>
                        auth()->id(),

                    'action' =>
                        'CREATE',

                    'module' =>
                        'Reservation',

                    'target_type' =>
                        Reservation::class,

                    'target_id' =>
                        $reservation->id,

                    'description' =>
                        'Reservation '
                        . $reservation->reservation_number
                        . ' was created.',

                    'old_value' =>
                        null,

                    'new_value' => [
                        'reservation_number' =>
                            $reservation->reservation_number,

                        'user_id' =>
                            $reservation->user_id,

                        'room_id' =>
                            $reservation->room_id,

                        'training_room_id' =>
                            $reservation->training_room_id,

                        'field_id' =>
                            $reservation->field_id,

                        'starts_at' =>
                            $reservation->starts_at?->toDateTimeString(),

                        'ends_at' =>
                            $reservation->ends_at?->toDateTimeString(),

                        'total_person' =>
                            $reservation->total_person,

                        'event_name' =>
                            $reservation->event_name,

                        'booker_name' =>
                            $reservation->booker_name,

                        'instructor' =>
                            $reservation->instructor,

                        'description' =>
                            $reservation->description,

                        'status' =>
                            $reservation->status,
                    ],
                ]);

                return $reservation;
            }
        );

        $notificationRoleNames = [
            'Coordinator',
        ];

        if ($resourceType === 'field') {
            $notificationRoleNames[] = 'Field Coordinator';
        }

        $coordinators = User::query()
            ->whereHas('role', function ($query) use (
                $notificationRoleNames
            ) {
                $query->whereIn(
                    'name',
                    $notificationRoleNames
                );
            })
            ->get();

        foreach ($coordinators as $coordinator) {
            Notification::create([
                'user_id' =>
                    $coordinator->id,

                'type' =>
                    'RESERVATION_PENDING',

                'title' =>
                    'New Reservation Pending',

                'message' =>
                    'Reservation '
                    . $reservation->reservation_number
                    . ' is waiting for your review.',

                'target_type' =>
                    'reservation',

                'target_id' =>
                    $reservation->id,

                'read_at' =>
                    null,
            ]);
        }

        return redirect()
            ->route(
                'reservations.show',
                $reservation
            )
            ->with(
                'success',
                'Reservation has been successfully created and is pending approval.'
            );
    }

    public function show(
        Reservation $reservation
    ): View {
        if (
            $this->isFieldCoordinator()
            && $reservation->field_id === null
        ) {
            abort(
                403,
                'Field Coordinators can only manage field reservations.'
            );
        }

        $reservation->load([
            'user',
            'room.building',
            'trainingRoom.building',
            'field',
        ]);

        return view(
            'reservations.show',
            [
                'reservation' => $reservation,
            ]
        );
    }

    public function edit(
        Reservation $reservation
    ): View {
        if (
            $this->isFieldCoordinator()
            && $reservation->field_id === null
        ) {
            abort(
                403,
                'Field Coordinators can only manage field reservations.'
            );
        }

        if (
            in_array(
                $reservation->status,
                [
                    'REJECTED',
                    'CANCELLED',
                ],
                true
            )
        ) {
            abort(
                403,
                'This reservation cannot be edited.'
            );
        }

        $reservation->load([
            'room.building',
            'trainingRoom.building',
            'field',
        ]);

        $users = User::query()
            ->orderBy('name')
            ->get();

        $rooms = collect();

        $trainingRooms = collect();

        $fields = Field::query()
            ->where(function ($query) use ($reservation) {
                $query
                    ->where(
                        'status',
                        'AVAILABLE'
                    )
                    ->orWhere(
                        'id',
                        $reservation->field_id
                    );
            })
            ->orderBy('name')
            ->get();

        if (!$this->isFieldCoordinator()) {
            $rooms = Room::query()
                ->with('building')
                ->where(function ($query) use ($reservation) {
                    $query
                        ->where(
                            'status',
                            'AVAILABLE'
                        )
                        ->orWhere(
                            'id',
                            $reservation->room_id
                        );
                })
                ->orderBy('building_id')
                ->orderBy('name')
                ->get();

            $trainingRooms = TrainingRoom::query()
                ->with('building')
                ->where(function ($query) use ($reservation) {
                    $query
                        ->where(
                            'status',
                            'AVAILABLE'
                        )
                        ->orWhere(
                            'id',
                            $reservation->training_room_id
                        );
                })
                ->orderBy('building_id')
                ->orderBy('name')
                ->get();
        }

        return view(
            'reservations.edit',
            [
                'reservation' => $reservation,
                'users' => $users,
                'rooms' => $rooms,
                'trainingRooms' => $trainingRooms,
                'fields' => $fields,
            ]
        );
    }

    public function update(
        Request $request,
        Reservation $reservation
    ): RedirectResponse {
        if (
            $this->isFieldCoordinator()
            && $reservation->field_id === null
        ) {
            abort(
                403,
                'Field Coordinators can only manage field reservations.'
            );
        }

        if (
            in_array(
                $reservation->status,
                [
                    'REJECTED',
                    'CANCELLED',
                ],
                true
            )
        ) {
            abort(
                403,
                'This reservation cannot be edited.'
            );
        }

        $validated = $request->validate([
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'resource_type' => [
                'required',
                Rule::in([
                    'room',
                    'training_room',
                    'field',
                ]),
            ],

            'resource_id' => [
                'required',
                'integer',
            ],

            'starts_at' => [
                'required',
                'date',
            ],

            'ends_at' => [
                'required',
                'date',
                'after:starts_at',
            ],

            'total_person' => [
                'required',
                'integer',
                'min:1',
            ],

            'event_name' => [
                'required',
                'string',
                'max:255',
            ],

            'booker_name' => [
                'required',
                'string',
                'max:255',
            ],

            'instructor' => [
                'nullable',
                'string',
            ],

            'description' => [
                'required',
                'string',
            ],
        ]);

        $resourceType = $validated['resource_type'];

        if (
            $this->isFieldCoordinator()
            && $resourceType !== 'field'
        ) {
            abort(
                403,
                'Field Coordinators can only manage field reservations.'
            );
        }

        $startsAt = Carbon::parse(
            $validated['starts_at']
        )->setTimezone(
            config('app.timezone')
        );

        $endsAt = Carbon::parse(
            $validated['ends_at']
        )->setTimezone(
            config('app.timezone')
        );

        if (!$startsAt->lt($endsAt)) {
            return back()
                ->withInput()
                ->withErrors([
                    'ends_at' =>
                        'The end date and time must be after the start date and time.',
                ]);
        }

        $resourceId =
            (int) $validated['resource_id'];

        $resource = match ($resourceType) {
            'room' =>
                Room::find($resourceId),

            'training_room' =>
                TrainingRoom::find($resourceId),

            'field' =>
                Field::find($resourceId),
        };

        if (!$resource) {
            return back()
                ->withInput()
                ->withErrors([
                    'resource_id' =>
                        'The selected resource could not be found.',
                ]);
        }

        $isCurrentResource =
            (
                $resourceType === 'room'
                && $reservation->room_id
                    === $resourceId
            )
            || (
                $resourceType === 'training_room'
                && $reservation->training_room_id
                    === $resourceId
            )
            || (
                $resourceType === 'field'
                && $reservation->field_id
                    === $resourceId
            );

        if (
            $resource->status !== 'AVAILABLE'
            && !$isCurrentResource
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'resource_id' =>
                        'The selected resource is currently under maintenance and cannot be reserved.',
                ]);
        }

        $resourceColumn = match ($resourceType) {
            'room' =>
                'room_id',

            'training_room' =>
                'training_room_id',

            'field' =>
                'field_id',
        };

        $hasOverlap = Reservation::query()
            ->where(
                $resourceColumn,
                $resourceId
            )
            ->whereIn('status', [
                'PENDING',
                'APPROVED',
            ])
            ->where(
                'id',
                '!=',
                $reservation->id
            )
            ->where(
                'starts_at',
                '<',
                $endsAt
            )
            ->where(
                'ends_at',
                '>',
                $startsAt
            )
            ->exists();

        if ($hasOverlap) {
            return back()
                ->withInput()
                ->withErrors([
                    'starts_at' =>
                        'The selected resource is already reserved during the requested time.',
                ]);
        }

        $oldValue = [
            'user_id' =>
                $reservation->user_id,

            'room_id' =>
                $reservation->room_id,

            'training_room_id' =>
                $reservation->training_room_id,

            'field_id' =>
                $reservation->field_id,

            'starts_at' =>
                $reservation->starts_at?->toDateTimeString(),

            'ends_at' =>
                $reservation->ends_at?->toDateTimeString(),

            'total_person' =>
                $reservation->total_person,

            'event_name' =>
                $reservation->event_name,

            'booker_name' =>
                $reservation->booker_name,

            'instructor' =>
                $reservation->instructor,

            'description' =>
                $reservation->description,

            'status' =>
                $reservation->status,
        ];

        $newValue = [
            'user_id' =>
                $validated['user_id'],

            'room_id' =>
                $resourceType === 'room'
                    ? $resourceId
                    : null,

            'training_room_id' =>
                $resourceType === 'training_room'
                    ? $resourceId
                    : null,

            'field_id' =>
                $resourceType === 'field'
                    ? $resourceId
                    : null,

            'starts_at' =>
                $startsAt->toDateTimeString(),

            'ends_at' =>
                $endsAt->toDateTimeString(),

            'total_person' =>
                $validated['total_person'],

            'event_name' =>
                $validated['event_name'],

            'booker_name' =>
                $validated['booker_name'],

            'instructor' =>
                $validated['instructor'] ?? null,

            'description' =>
                $validated['description'],

            'status' =>
                $reservation->status,
        ];

        DB::transaction(
            function () use (
                $reservation,
                $newValue,
                $oldValue
            ) {
                $reservation->update([
                    'user_id' =>
                        $newValue['user_id'],

                    'room_id' =>
                        $newValue['room_id'],

                    'training_room_id' =>
                        $newValue['training_room_id'],

                    'field_id' =>
                        $newValue['field_id'],

                    'starts_at' =>
                        $newValue['starts_at'],

                    'ends_at' =>
                        $newValue['ends_at'],

                    'total_person' =>
                        $newValue['total_person'],

                    'event_name' =>
                        $newValue['event_name'],

                    'booker_name' =>
                        $newValue['booker_name'],

                    'instructor' =>
                        $newValue['instructor'],

                    'description' =>
                        $newValue['description'],
                ]);

                AuditLog::create([
                    'user_id' =>
                        auth()->id(),

                    'action' =>
                        'UPDATE',

                    'module' =>
                        'Reservation',

                    'target_type' =>
                        Reservation::class,

                    'target_id' =>
                        $reservation->id,

                    'description' =>
                        'Reservation '
                        . $reservation->reservation_number
                        . ' was updated.',

                    'old_value' =>
                        $oldValue,

                    'new_value' =>
                        $newValue,
                ]);
            }
        );

        return redirect()
            ->route(
                'reservations.show',
                $reservation
            )
            ->with(
                'success',
                'Reservation has been successfully updated.'
            );
    }

    public function destroy(
        Reservation $reservation
    ): RedirectResponse {
        if (
            $this->isFieldCoordinator()
            && $reservation->field_id === null
        ) {
            abort(
                403,
                'Field Coordinators can only manage field reservations.'
            );
        }

        if (
            !in_array(
                $reservation->status,
                [
                    'PENDING',
                    'APPROVED',
                ],
                true
            )
        ) {
            return redirect()
                ->route('reservations.index')
                ->with(
                    'error',
                    'This reservation cannot be cancelled.'
                );
        }

        DB::transaction(function () use ($reservation) {
            $oldValue = [
                'status' =>
                    $reservation->status,
            ];

            $reservation->update([
                'status' =>
                    'CANCELLED',
            ]);

            AuditLog::create([
                'user_id' =>
                    auth()->id(),

                'action' =>
                    'CANCEL',

                'module' =>
                    'Reservation',

                'target_type' =>
                    Reservation::class,

                'target_id' =>
                    $reservation->id,

                'description' =>
                    'Reservation '
                    . $reservation->reservation_number
                    . ' was cancelled.',

                'old_value' =>
                    $oldValue,

                'new_value' => [
                    'status' =>
                        'CANCELLED',
                ],
            ]);
        });

        return redirect()
            ->route('reservations.index')
            ->with(
                'success',
                'Reservation has been successfully cancelled.'
            );
    }

    private function isFieldCoordinator(): bool
    {
        return auth()->user()?->role?->name
            === 'Field Coordinator';
    }

    private function generateReservationNumber(): string
    {
        do {
            $number =
                'RSV-'
                . now()->format('Ymd')
                . '-'
                . strtoupper(
                    Str::random(6)
                );

        } while (
            Reservation::where(
                'reservation_number',
                $number
            )->exists()
        );

        return $number;
    }
}