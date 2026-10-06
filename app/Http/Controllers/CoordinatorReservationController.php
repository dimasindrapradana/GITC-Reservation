<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Building;
use App\Models\Field;
use App\Models\Notification;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\TrainingRoom;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CoordinatorReservationController extends Controller
{
    public function index(Request $request): View
    {
        $isFieldCoordinator = $this->isFieldCoordinator();

        $search = $request->string('search')
            ->trim()
            ->toString();

        $building = $request->string('building')
            ->toString();

        $status = $request->string('status')
            ->toString();

        $resourceType = $request->string('resource_type')
            ->toString();

        $dateFrom = $request->string('date_from')
            ->toString();

        $dateTo = $request->string('date_to')
            ->toString();

        $sort = $request->string('sort')
            ->toString();

        $allowedSorts = [
            'newest',
            'oldest',
        ];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'newest';
        }

        if ($isFieldCoordinator) {
            $resourceType = 'field';
            $building = '';
        }

        /*
         * Validate date filters.
         */
        if ($dateFrom !== '') {
            try {
                Carbon::createFromFormat('Y-m-d', $dateFrom);
            } catch (\Throwable $e) {
                $dateFrom = '';
            }
        }

        if ($dateTo !== '') {
            try {
                Carbon::createFromFormat('Y-m-d', $dateTo);
            } catch (\Throwable $e) {
                $dateTo = '';
            }
        }

        /*
         * If Date From is later than Date To,
         * reset Date To so the query remains valid.
         */
        if ($dateFrom !== '' && $dateTo !== '') {
            $fromDate = Carbon::createFromFormat(
                'Y-m-d',
                $dateFrom
            );

            $toDate = Carbon::createFromFormat(
                'Y-m-d',
                $dateTo
            );

            if ($fromDate->gt($toDate)) {
                $dateTo = '';
            }
        }

        $reservations = Reservation::query()
            ->with([
                'user',
                'room.building',
                'trainingRoom.building',
                'field',
            ])

            /*
             * Search
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
                            'instructor',
                            'like',
                            '%' . $search . '%'
                        )

                        ->orWhere(
                            'description',
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
             * Building filter
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
             * Status filter
             */
            ->when($status !== '', function ($query) use ($status) {

                $query->where(
                    'status',
                    $status
                );

            })

            /*
             * Resource type filter
             */
            ->when(
                $resourceType === 'room',
                function ($query) {

                    $query->whereNotNull(
                        'room_id'
                    );

                }
            )

            ->when(
                $resourceType === 'training_room',
                function ($query) {

                    $query->whereNotNull(
                        'training_room_id'
                    );

                }
            )

            ->when(
                $resourceType === 'field',
                function ($query) {

                    $query->whereNotNull(
                        'field_id'
                    );

                }
            )

            /*
             * Date From filter
             *
             * Reservation starts on or after this date.
             */
            ->when(
                $dateFrom !== '',
                function ($query) use ($dateFrom) {

                    $query->where(
                        'starts_at',
                        '>=',
                        Carbon::createFromFormat(
                            'Y-m-d',
                            $dateFrom
                        )->startOfDay()
                    );

                }
            )

            /*
             * Date To filter
             *
             * Reservation starts on or before this date.
             */
            ->when(
                $dateTo !== '',
                function ($query) use ($dateTo) {

                    $query->where(
                        'starts_at',
                        '<=',
                        Carbon::createFromFormat(
                            'Y-m-d',
                            $dateTo
                        )->endOfDay()
                    );

                }
            );

        /*
         * Sorting
         */
        if ($sort === 'oldest') {

            $reservations
                ->orderBy(
                    'created_at',
                    'asc'
                )
                ->orderBy(
                    'id',
                    'asc'
                );

        } else {

            $reservations
                ->orderBy(
                    'created_at',
                    'desc'
                )
                ->orderBy(
                    'id',
                    'desc'
                );
        }

        /*
         * Pagination
         */
        $reservations = $reservations
            ->paginate(10)
            ->withQueryString();

        /*
         * Building list
         */
        $buildings = $isFieldCoordinator
            ? collect()
            : Building::query()
                ->orderBy('name')
                ->get();

        return view(
            'coordinator.reservations.index',
            [
                'reservations' => $reservations,
                'buildings' => $buildings,
                'search' => $search,
                'building' => $building,
                'status' => $status,
                'resourceType' => $resourceType,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'sort' => $sort,
            ]
        );
    }

    public function show(Reservation $reservation): View
    {
        $this->ensureReservationAccess($reservation);

        $reservation->load([
            'user',
            'room.building',
            'room.images',
            'trainingRoom.building',
            'trainingRoom.images',
            'field.images',
        ]);

        return view('coordinator.reservations.show', [
            'reservation' => $reservation,
        ]);
    }

    public function edit(Reservation $reservation): View
    {
        $this->ensureReservationAccess($reservation);

        if (!in_array(
            $reservation->status,
            ['PENDING', 'APPROVED'],
            true
        )) {
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

        if ($this->isFieldCoordinator()) {

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

        } else {

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
        }

        return view('coordinator.reservations.edit', [
            'reservation' => $reservation,
            'rooms' => $rooms,
            'trainingRooms' => $trainingRooms,
            'fields' => $fields,
        ]);
    }

    public function update(
        Request $request,
        Reservation $reservation
    ): RedirectResponse {
        $this->ensureReservationAccess($reservation);

        if (!in_array(
            $reservation->status,
            ['PENDING', 'APPROVED'],
            true
        )) {
            return back()->with(
                'error',
                'This reservation cannot be edited.'
            );
        }

        $validated = $request->validate([
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

        if (
            $this->isFieldCoordinator()
            && $validated['resource_type'] !== 'field'
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

        $resourceType = $validated['resource_type'];

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

        $isCurrentResource =
            (
                $resourceType === 'room'
                && $reservation->room_id === $resourceId
            )
            || (
                $resourceType === 'training_room'
                && $reservation->training_room_id === $resourceId
            )
            || (
                $resourceType === 'field'
                && $reservation->field_id === $resourceId
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
            'room_id' => $reservation->room_id,
            'training_room_id' => $reservation->training_room_id,
            'field_id' => $reservation->field_id,
            'starts_at' => $reservation->starts_at?->toDateTimeString(),
            'ends_at' => $reservation->ends_at?->toDateTimeString(),
            'total_person' => $reservation->total_person,
            'event_name' => $reservation->event_name,
            'booker_name' => $reservation->booker_name,
            'instructor' => $reservation->instructor,
            'description' => $reservation->description,
            'status' => $reservation->status,
        ];

        $newValue = [
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

        DB::transaction(function () use (
            $reservation,
            $newValue,
            $oldValue
        ) {
            $reservation->update([
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
        });

        return redirect()
            ->route(
                $this->reservationRoute('show'),
                $reservation
            )
            ->with(
                'success',
                'Reservation has been successfully updated.'
            );
    }

    public function approve(Reservation $reservation): RedirectResponse
    {
        $this->ensureReservationAccess($reservation);

        if ($reservation->status !== 'PENDING') {
            return back()->with(
                'error',
                'Only pending reservations can be approved.'
            );
        }

        $reservation->load([
            'room',
            'trainingRoom',
            'field',
        ]);

        $resource = $this->getResource($reservation);

        if ($resource === null) {
            return back()->with(
                'error',
                'The reservation resource could not be found.'
            );
        }

        if ($resource->status !== 'AVAILABLE') {
            return back()->with(
                'error',
                'This resource is currently under maintenance.'
            );
        }

        $hasOverlap = Reservation::query()
            ->where('id', '!=', $reservation->id)
            ->whereIn('status', [
                'PENDING',
                'APPROVED',
            ])
            ->where(
                'starts_at',
                '<',
                $reservation->ends_at
            )
            ->where(
                'ends_at',
                '>',
                $reservation->starts_at
            )
            ->where(function ($query) use ($reservation) {

                $query
                    ->when(
                        $reservation->room_id !== null,
                        fn ($query) => $query->where(
                            'room_id',
                            $reservation->room_id
                        )
                    )
                    ->when(
                        $reservation->training_room_id !== null,
                        fn ($query) => $query->where(
                            'training_room_id',
                            $reservation->training_room_id
                        )
                    )
                    ->when(
                        $reservation->field_id !== null,
                        fn ($query) => $query->where(
                            'field_id',
                            $reservation->field_id
                        )
                    );

            })
            ->exists();

        if ($hasOverlap) {
            return back()->with(
                'error',
                'This reservation cannot be approved because the selected time slot is no longer available.'
            );
        }

        DB::transaction(function () use ($reservation) {

            $oldValue = [
                'status' => $reservation->status,
            ];

            $reservation->update([
                'status' => 'APPROVED',
                'rejection_reason' => null,
            ]);

            $this->sendReservationStatusNotification(
                $reservation,
                'APPROVED'
            );

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'APPROVE',
                'module' => 'Reservation',
                'target_type' => Reservation::class,
                'target_id' => $reservation->id,
                'description' =>
                    'Reservation ' .
                    $reservation->reservation_number .
                    ' was approved.',
                'old_value' => $oldValue,
                'new_value' => [
                    'status' => 'APPROVED',
                ],
            ]);
        });

        return redirect()
            ->route(
                $this->reservationRoute('show'),
                $reservation
            )
            ->with(
                'success',
                'Reservation approved successfully.'
            );
    }

    public function reject(
        Request $request,
        Reservation $reservation
    ): RedirectResponse {
        $this->ensureReservationAccess($reservation);

        if ($reservation->status !== 'PENDING') {
            return back()->with(
                'error',
                'Only pending reservations can be rejected.'
            );
        }

        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        DB::transaction(function () use (
            $reservation,
            $validated
        ) {

            $oldValue = [
                'status' => $reservation->status,
                'rejection_reason' => $reservation->rejection_reason,
            ];

            $reservation->update([
                'status' => 'REJECTED',
                'rejection_reason' => $validated['rejection_reason'],
            ]);

            $this->sendReservationStatusNotification(
                $reservation,
                'REJECTED'
            );

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'REJECT',
                'module' => 'Reservation',
                'target_type' => Reservation::class,
                'target_id' => $reservation->id,
                'description' =>
                    'Reservation ' .
                    $reservation->reservation_number .
                    ' was rejected.',
                'old_value' => $oldValue,
                'new_value' => [
                    'status' => 'REJECTED',
                    'rejection_reason' => $validated['rejection_reason'],
                ],
            ]);
        });

        return redirect()
            ->route(
                $this->reservationRoute('show'),
                $reservation
            )
            ->with(
                'success',
                'Reservation rejected successfully.'
            );
    }

    public function cancel(Reservation $reservation): RedirectResponse
    {
        $this->ensureReservationAccess($reservation);

        if (!in_array(
            $reservation->status,
            ['PENDING', 'APPROVED'],
            true
        )) {
            return back()->with(
                'error',
                'This reservation cannot be cancelled.'
            );
        }

        DB::transaction(function () use ($reservation) {

            $oldValue = [
                'status' => $reservation->status,
            ];

            $reservation->update([
                'status' => 'CANCELLED',
            ]);

            $this->sendReservationStatusNotification(
                $reservation,
                'CANCELLED'
            );

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'CANCEL',
                'module' => 'Reservation',
                'target_type' => Reservation::class,
                'target_id' => $reservation->id,
                'description' =>
                    'Reservation ' .
                    $reservation->reservation_number .
                    ' was cancelled.',
                'old_value' => $oldValue,
                'new_value' => [
                    'status' => 'CANCELLED',
                ],
            ]);
        });

        return redirect()
            ->route(
                $this->reservationRoute('show'),
                $reservation
            )
            ->with(
                'success',
                'Reservation cancelled successfully.'
            );
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        $this->ensureReservationAccess($reservation);

        if (!in_array(
            $reservation->status,
            ['PENDING', 'APPROVED'],
            true
        )) {
            return back()->with(
                'error',
                'This reservation cannot be deleted.'
            );
        }

        DB::transaction(function () use ($reservation) {

            $oldValue = [
                'status' => $reservation->status,
            ];

            $reservation->update([
                'status' => 'CANCELLED',
            ]);

            $this->sendReservationStatusNotification(
                $reservation,
                'CANCELLED'
            );

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'DELETE',
                'module' => 'Reservation',
                'target_type' => Reservation::class,
                'target_id' => $reservation->id,
                'description' =>
                    'Reservation ' .
                    $reservation->reservation_number .
                    ' was deleted by cancellation.',
                'old_value' => $oldValue,
                'new_value' => [
                    'status' => 'CANCELLED',
                ],
            ]);
        });

        return redirect()
            ->route(
                $this->reservationRoute('index')
            )
            ->with(
                'success',
                'Reservation has been successfully deleted.'
            );
    }

    /*
     * Send notification to the reservation requester
     * whenever the reservation status changes.
     */
    private function sendReservationStatusNotification(
        Reservation $reservation,
        string $status
    ): void {
        $messages = [
            'APPROVED' => [
                'title' => 'Reservation Approved',
                'message' =>
                    'Your reservation '
                    . $reservation->reservation_number
                    . ' has been approved.',
            ],

            'REJECTED' => [
                'title' => 'Reservation Rejected',
                'message' =>
                    'Your reservation '
                    . $reservation->reservation_number
                    . ' has been rejected.',
            ],

            'CANCELLED' => [
                'title' => 'Reservation Cancelled',
                'message' =>
                    'Your reservation '
                    . $reservation->reservation_number
                    . ' has been cancelled.',
            ],
        ];

        if (!isset($messages[$status])) {
            return;
        }

        Notification::create([
            'user_id' => $reservation->user_id,
            'type' => 'RESERVATION_STATUS_CHANGED',
            'title' => $messages[$status]['title'],
            'message' => $messages[$status]['message'],
            'target_type' => 'reservation',
            'target_id' => $reservation->id,
            'read_at' => null,
        ]);
    }

    private function isFieldCoordinator(): bool
    {
        return auth()->user()?->role?->name === 'Field Coordinator';
    }

    private function ensureReservationAccess(
        Reservation $reservation
    ): void {
        if (
            $this->isFieldCoordinator()
            && $reservation->field_id === null
        ) {
            abort(
                403,
                'Field Coordinators can only manage field reservations.'
            );
        }
    }

    private function reservationRoute(string $action): string
    {
        if ($this->isFieldCoordinator()) {
            return 'field-coordinator.reservations.' . $action;
        }

        return 'coordinator.reservations.' . $action;
    }

    private function getResource(Reservation $reservation)
    {
        if ($reservation->room !== null) {
            return $reservation->room;
        }

        if ($reservation->trainingRoom !== null) {
            return $reservation->trainingRoom;
        }

        if ($reservation->field !== null) {
            return $reservation->field;
        }

        return null;
    }
}