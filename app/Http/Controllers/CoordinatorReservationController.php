<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CoordinatorReservationController extends Controller
{
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

        $allowedSorts = [
            'created_desc',
            'created_asc',
            'schedule_desc',
            'schedule_asc',
        ];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'schedule_desc';
        }

        $reservations = Reservation::query()
            ->with([
                'user',
                'room.building',
                'trainingRoom.building',
                'field',
            ])
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
            ->when($building !== '', function ($query) use ($building) {
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
            })
            ->when($resourceType === 'field', function ($query) {
                $query->whereNotNull('field_id');
            });

        switch ($sort) {
            case 'created_asc':
                $reservations->orderBy(
                    'created_at',
                    'asc'
                );
                break;

            case 'schedule_desc':
                $reservations->orderBy(
                    'starts_at',
                    'desc'
                );
                break;

            case 'schedule_asc':
                $reservations->orderBy(
                    'starts_at',
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

        return view('coordinator.reservations.index', [
            'reservations' => $reservations,
            'buildings' => $buildings,
            'search' => $search,
            'building' => $building,
            'status' => $status,
            'resourceType' => $resourceType,
            'sort' => $sort,
        ]);
    }

    public function show(Reservation $reservation): View
    {
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

    public function approve(Reservation $reservation): RedirectResponse
    {
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
            ->where('starts_at', '<', $reservation->ends_at)
            ->where('ends_at', '>', $reservation->starts_at)
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

            \App\Models\AuditLog::create([
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
                'coordinator.reservations.show',
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

            \App\Models\AuditLog::create([
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
                'coordinator.reservations.show',
                $reservation
            )
            ->with(
                'success',
                'Reservation rejected successfully.'
            );
    }

    public function cancel(Reservation $reservation): RedirectResponse
    {
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

            \App\Models\AuditLog::create([
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
                'coordinator.reservations.show',
                $reservation
            )
            ->with(
                'success',
                'Reservation cancelled successfully.'
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

        if ($reservation->field !== null) {
            return $reservation->field;
        }

        return null;
    }
}