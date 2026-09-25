<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Building;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\TrainingRoom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BuildingCoordinatorReservationController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $buildingIds = $user->buildings()
            ->pluck('buildings.id');

        abort_if($buildingIds->isEmpty(), 403);

        $search = $request->string('search')
            ->trim()
            ->toString();

        $status = $request->string('status')
            ->toString();

        $resourceType = $request->string('resource_type')
            ->toString();

        $sort = $request->string('sort')
            ->toString();

        $allowedSorts = [
            'newest',
            'latest',
        ];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'newest';
        }

        $reservations = Reservation::query()
            ->with([
                'user',
                'room.building',
                'trainingRoom.building',
            ])
            ->where(function ($query) use ($buildingIds) {
                $query
                    ->whereHas('room', function ($query) use ($buildingIds) {
                        $query->whereIn(
                            'building_id',
                            $buildingIds
                        );
                    })
                    ->orWhereHas('trainingRoom', function ($query) use ($buildingIds) {
                        $query->whereIn(
                            'building_id',
                            $buildingIds
                        );
                    });
            })
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
                        });
                });
            })
            ->when($status !== '', function ($query) use ($status) {
                $query->where(
                    'status',
                    $status
                );
            })
            ->when($resourceType === 'room', function ($query) {
                $query->whereNotNull('room_id');
            })
            ->when($resourceType === 'training_room', function ($query) {
                $query->whereNotNull('training_room_id');
            });

        if ($sort === 'latest') {
            $reservations
                ->orderBy('created_at', 'asc')
                ->orderBy('id', 'asc');
        } else {
            $reservations
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc');
        }

        $reservations = $reservations
            ->paginate(10)
            ->withQueryString();

        return view('building-coordinator.reservations.index', [
            'reservations' => $reservations,
            'search' => $search,
            'status' => $status,
            'resourceType' => $resourceType,
            'sort' => $sort,
        ]);
    }

    public function show(Reservation $reservation): View
    {
        $this->authorizeReservation($reservation);

        $reservation->load([
            'user',
            'room.building',
            'room.images',
            'trainingRoom.building',
            'trainingRoom.images',
        ]);

        return view('building-coordinator.reservations.show', [
            'reservation' => $reservation,
        ]);
    }

    public function edit(Reservation $reservation): View
    {
        $this->authorizeReservation($reservation);

        if (!in_array(
            $reservation->status,
            [
                'PENDING',
                'APPROVED',
            ],
            true
        )) {
            abort(
                403,
                'Only pending or approved reservations can be edited.'
            );
        }

        $user = auth()->user();

        $buildingIds = $user->buildings()
            ->pluck('buildings.id');

        abort_if($buildingIds->isEmpty(), 403);

        $reservation->load([
            'user',
            'room.building',
            'trainingRoom.building',
        ]);

        $buildings = Building::query()
            ->whereIn('id', $buildingIds)
            ->orderBy('name')
            ->get();

        $rooms = Room::query()
            ->with('building')
            ->whereIn('building_id', $buildingIds)
            ->where('status', 'AVAILABLE')
            ->orderBy('name')
            ->get();

        $trainingRooms = TrainingRoom::query()
            ->with('building')
            ->whereIn('building_id', $buildingIds)
            ->where('status', 'AVAILABLE')
            ->orderBy('name')
            ->get();

        if ($reservation->room_id !== null) {
            $currentRoom = Room::query()
                ->with('building')
                ->whereKey($reservation->room_id)
                ->whereIn('building_id', $buildingIds)
                ->first();

            if (
                $currentRoom !== null &&
                !$rooms->contains('id', $currentRoom->id)
            ) {
                $rooms->push($currentRoom);
            }
        }

        if ($reservation->training_room_id !== null) {
            $currentTrainingRoom = TrainingRoom::query()
                ->with('building')
                ->whereKey($reservation->training_room_id)
                ->whereIn('building_id', $buildingIds)
                ->first();

            if (
                $currentTrainingRoom !== null &&
                !$trainingRooms->contains('id', $currentTrainingRoom->id)
            ) {
                $trainingRooms->push($currentTrainingRoom);
            }
        }

        return view('building-coordinator.reservations.edit', [
            'reservation' => $reservation,
            'buildings' => $buildings,
            'rooms' => $rooms,
            'trainingRooms' => $trainingRooms,
        ]);
    }

    public function update(
        Request $request,
        Reservation $reservation
    ): RedirectResponse {
        $this->authorizeReservation($reservation);

        if (!in_array(
            $reservation->status,
            [
                'PENDING',
                'APPROVED',
            ],
            true
        )) {
            return back()->with(
                'error',
                'Only pending or approved reservations can be edited.'
            );
        }

        $validated = $request->validate([
            'resource_type' => [
                'required',
                'string',
                'in:room,training_room',
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

        $user = auth()->user();

        $buildingIds = $user->buildings()
            ->pluck('buildings.id');

        abort_if($buildingIds->isEmpty(), 403);

        $startsAt = \Illuminate\Support\Carbon::parse(
            $validated['starts_at'],
            config('app.timezone')
        );

        $endsAt = \Illuminate\Support\Carbon::parse(
            $validated['ends_at'],
            config('app.timezone')
        );

        if ($endsAt->lessThanOrEqualTo($startsAt)) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'The end time must be later than the start time.'
                );
        }

        $roomId = null;
        $trainingRoomId = null;
        $resource = null;

        if ($validated['resource_type'] === 'room') {
            $resource = Room::query()
                ->whereKey($validated['resource_id'])
                ->whereIn('building_id', $buildingIds)
                ->first();
        }

        if ($validated['resource_type'] === 'training_room') {
            $resource = TrainingRoom::query()
                ->whereKey($validated['resource_id'])
                ->whereIn('building_id', $buildingIds)
                ->first();
        }

        if ($resource === null) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'The selected resource is not available within your assigned building.'
                );
        }

        $isCurrentResource =
            (
                $validated['resource_type'] === 'room' &&
                $reservation->room_id === $resource->id
            )
            ||
            (
                $validated['resource_type'] === 'training_room' &&
                $reservation->training_room_id === $resource->id
            );

        if (
            $resource->status !== 'AVAILABLE' &&
            !$isCurrentResource
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'The selected resource is currently under maintenance.'
                );
        }

        $overlapQuery = Reservation::query()
            ->where(
                'id',
                '!=',
                $reservation->id
            )
            ->whereIn(
                'status',
                [
                    'PENDING',
                    'APPROVED',
                ]
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
            );

        if ($validated['resource_type'] === 'room') {
            $roomId = $resource->id;

            $overlapQuery->where(
                'room_id',
                $roomId
            );
        } else {
            $trainingRoomId = $resource->id;

            $overlapQuery->where(
                'training_room_id',
                $trainingRoomId
            );
        }

        if ($overlapQuery->exists()) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'The selected resource is already reserved during the requested time.'
                );
        }

        DB::transaction(function () use (
            $reservation,
            $validated,
            $startsAt,
            $endsAt,
            $roomId,
            $trainingRoomId
        ) {
            $oldValue = [
                'room_id' => $reservation->room_id,
                'training_room_id' => $reservation->training_room_id,
                'starts_at' => $reservation->starts_at,
                'ends_at' => $reservation->ends_at,
                'total_person' => $reservation->total_person,
                'event_name' => $reservation->event_name,
                'booker_name' => $reservation->booker_name,
                'instructor' => $reservation->instructor,
                'description' => $reservation->description,
            ];

            $reservation->update([
                'room_id' => $roomId,
                'training_room_id' => $trainingRoomId,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'total_person' => $validated['total_person'],
                'event_name' => $validated['event_name'],
                'booker_name' => $validated['booker_name'],
                'instructor' => $validated['instructor'] ?? null,
                'description' => $validated['description'],
            ]);

            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'UPDATE',
                'module' => 'Reservation',
                'target_type' => Reservation::class,
                'target_id' => $reservation->id,
                'description' =>
                    'Reservation ' .
                    $reservation->reservation_number .
                    ' was updated.',
                'old_value' => $oldValue,
                'new_value' => [
                    'room_id' => $reservation->room_id,
                    'training_room_id' => $reservation->training_room_id,
                    'starts_at' => $reservation->starts_at,
                    'ends_at' => $reservation->ends_at,
                    'total_person' => $reservation->total_person,
                    'event_name' => $reservation->event_name,
                    'booker_name' => $reservation->booker_name,
                    'instructor' => $reservation->instructor,
                    'description' => $reservation->description,
                ],
            ]);
        });

        return redirect()
            ->route(
                'building-coordinator.reservations.show',
                $reservation
            )
            ->with(
                'success',
                'Reservation updated successfully.'
            );
    }

    public function approve(
        Reservation $reservation
    ): RedirectResponse {
        $this->authorizeReservation($reservation);

        if ($reservation->status !== 'PENDING') {
            return back()->with(
                'error',
                'Only pending reservations can be approved.'
            );
        }

        $reservation->load([
            'room',
            'trainingRoom',
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
            ->where(
                'id',
                '!=',
                $reservation->id
            )
            ->whereIn(
                'status',
                [
                    'PENDING',
                    'APPROVED',
                ]
            )
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
                if ($reservation->room_id !== null) {
                    $query->where(
                        'room_id',
                        $reservation->room_id
                    );
                }

                if ($reservation->training_room_id !== null) {
                    $query->where(
                        'training_room_id',
                        $reservation->training_room_id
                    );
                }
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
                'building-coordinator.reservations.show',
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
        $this->authorizeReservation($reservation);

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
                'building-coordinator.reservations.show',
                $reservation
            )
            ->with(
                'success',
                'Reservation rejected successfully.'
            );
    }

    public function cancel(
        Reservation $reservation
    ): RedirectResponse {
        $this->authorizeReservation($reservation);

        if (!in_array(
            $reservation->status,
            [
                'PENDING',
                'APPROVED',
            ],
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
                'building-coordinator.reservations.show',
                $reservation
            )
            ->with(
                'success',
                'Reservation cancelled successfully.'
            );
    }

    private function authorizeReservation(
        Reservation $reservation
    ): void {
        $user = auth()->user();

        $buildingIds = $user->buildings()
            ->pluck('buildings.id');

        abort_if(
            $buildingIds->isEmpty(),
            403
        );

        $belongsToBuilding = Reservation::query()
            ->whereKey($reservation->id)
            ->where(function ($query) use ($buildingIds) {
                $query
                    ->whereHas('room', function ($query) use ($buildingIds) {
                        $query->whereIn(
                            'building_id',
                            $buildingIds
                        );
                    })
                    ->orWhereHas('trainingRoom', function ($query) use ($buildingIds) {
                        $query->whereIn(
                            'building_id',
                            $buildingIds
                        );
                    });
            })
            ->exists();

        abort_unless(
            $belongsToBuilding,
            403
        );
    }

    private function getResource(Reservation $reservation)
    {
        if ($reservation->room !== null) {
            return $reservation->room;
        }

        if ($reservation->trainingRoom !== null) {
            return $reservation->trainingRoom;
        }

        return null;
    }
}