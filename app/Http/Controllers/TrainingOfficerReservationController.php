<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Notification;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\TrainingRoom;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TrainingOfficerReservationController extends Controller
{
    /**
     * Start the reservation process.
     */
    public function create(Request $request): RedirectResponse
    {
        $resources = $this->getCartResources($request);

        if ($resources->isEmpty()) {
            return redirect()
                ->route('training-officer.cart')
                ->with('error', 'Your reservation cart is empty.');
        }

        return redirect()->route(
            'training-officer.reservation.resource.create',
            [
                'type' => $resources->first()['type'],
                'resource' => $resources->first()['id'],
            ]
        );
    }

    /**
     * Show reservation form for one resource.
     */
    public function createResource(
        Request $request,
        string $type,
        int $resource
    ): View|RedirectResponse {
        $resources = $this->getCartResources($request);

        if ($resources->isEmpty()) {
            return redirect()
                ->route('training-officer.cart')
                ->with('error', 'Your reservation cart is empty.');
        }

        $currentIndex = $resources->search(
            fn ($item) =>
                $item['type'] === $type
                && $item['id'] === $resource
        );

        if ($currentIndex === false) {
            return redirect()
                ->route('training-officer.reservation.create');
        }

        $selectedResource = $resources->get($currentIndex);

        $reservationData = $request->session()->get(
            'training_officer_reservations',
            []
        );

        $currentData = $reservationData[$currentIndex] ?? [];

        /*
         * When editing from the review page, keep the current
         * resource form in edit mode.
         */
        $isEdit = $request->boolean('edit');

        return view(
            'training-officer.reservation.create',
            [
                'resource' => $selectedResource,
                'type' => $type,
                'resourceId' => $resource,
                'step' => $currentIndex + 1,
                'totalSteps' => $resources->count(),
                'currentData' => $currentData,
                'isEdit' => $isEdit,
            ]
        );
    }

    /**
     * Store reservation data for one resource.
     */
    public function storeResource(
        Request $request,
        string $type,
        int $resource
    ): RedirectResponse {
        $resources = $this->getCartResources($request);

        if ($resources->isEmpty()) {
            return redirect()
                ->route('training-officer.cart')
                ->with('error', 'Your reservation cart is empty.');
        }

        $currentIndex = $resources->search(
            fn ($item) =>
                $item['type'] === $type
                && $item['id'] === $resource
        );

        if ($currentIndex === false) {
            return redirect()
                ->route('training-officer.reservation.create');
        }

        $selectedResource = $resources->get($currentIndex);

        $validated = $request->validate([
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

        /*
         * Validate capacity.
         */
        $this->validateCapacity(
            $validated['total_person'],
            $selectedResource['capacity'],
            $selectedResource['name']
        );

        /*
         * Check schedule conflict.
         */
        $this->validateScheduleConflict(
            $selectedResource['column'],
            $selectedResource['id'],
            $validated['starts_at'],
            $validated['ends_at'],
            $selectedResource['name'],
            $request->boolean('edit')
                ? ($request->session()->get(
                    'training_officer_reservations',
                    []
                )[$currentIndex]['reservation_id'] ?? null)
                : null
        );

        /*
         * Save resource data into session.
         */
        $reservationData = $request->session()->get(
            'training_officer_reservations',
            []
        );

        $existingData = $reservationData[$currentIndex] ?? [];

        $reservationData[$currentIndex] = [
            'reservation_id' => $existingData['reservation_id'] ?? null,

            'resource_type' => $selectedResource['type'],

            'resource_id' => $selectedResource['id'],

            'resource_name' => $selectedResource['name'],

            'starts_at' => $validated['starts_at'],

            'ends_at' => $validated['ends_at'],

            'total_person' => $validated['total_person'],

            'event_name' => $validated['event_name'],

            'booker_name' => $validated['booker_name'],

            'instructor' => $validated['instructor'] ?? null,

            'description' => $validated['description'],
        ];

        $request->session()->put(
            'training_officer_reservations',
            $reservationData
        );

        /*
         * IMPORTANT:
         *
         * If user came from Review > Edit,
         * do NOT continue through the remaining resources.
         *
         * Save the edited data and return directly
         * to the Review page.
         */
        if ($request->boolean('edit')) {
            return redirect()
                ->route('training-officer.reservation.review');
        }

        /*
         * Normal reservation flow.
         */
        $nextResource = $resources->get($currentIndex + 1);

        if ($nextResource) {
            return redirect()->route(
                'training-officer.reservation.resource.create',
                [
                    'type' => $nextResource['type'],
                    'resource' => $nextResource['id'],
                ]
            );
        }

        /*
         * All resources completed.
         */
        return redirect()
            ->route('training-officer.reservation.review');
    }

    /**
     * Show final review page.
     */
    public function review(Request $request): View|RedirectResponse
    {
        $resources = $this->getCartResources($request);

        if ($resources->isEmpty()) {
            return redirect()
                ->route('training-officer.cart')
                ->with('error', 'Your reservation cart is empty.');
        }

        $reservationData = $request->session()->get(
            'training_officer_reservations',
            []
        );

        if (count($reservationData) !== $resources->count()) {
            return redirect()
                ->route('training-officer.reservation.create')
                ->with(
                    'error',
                    'Please complete all reservation forms before reviewing.'
                );
        }

        return view(
            'training-officer.reservation.review',
            [
                'reservations' => $reservationData,
                'totalItems' => $resources->count(),
            ]
        );
    }

    /**
     * Final reservation submission.
     */
    public function submit(Request $request): RedirectResponse
    {
        $resources = $this->getCartResources($request);

        if ($resources->isEmpty()) {
            return redirect()
                ->route('training-officer.cart')
                ->with('error', 'Your reservation cart is empty.');
        }

        $reservationData = $request->session()->get(
            'training_officer_reservations',
            []
        );

        if (count($reservationData) !== $resources->count()) {
            return redirect()
                ->route('training-officer.reservation.create')
                ->with(
                    'error',
                    'Please complete all reservation forms before submitting.'
                );
        }

        /*
         * Re-check availability and conflicts
         * immediately before database insertion.
         */
        foreach ($reservationData as $data) {
            $resource = $this->findResource(
                $data['resource_type'],
                $data['resource_id']
            );

            if (!$resource || $resource->status !== 'AVAILABLE') {
                return redirect()
                    ->route('training-officer.cart')
                    ->with(
                        'error',
                        $data['resource_name']
                        . ' is no longer available.'
                    );
            }

            $this->validateCapacity(
                $data['total_person'],
                $resource->capacity,
                $resource->name
            );

            $column = match ($data['resource_type']) {
                'room' => 'room_id',

                'training_room' => 'training_room_id',

                'field' => 'field_id',

                default => null,
            };

            if (!$column) {
                return redirect()
                    ->route('training-officer.cart')
                    ->with(
                        'error',
                        'Invalid reservation resource.'
                    );
            }

            $this->validateScheduleConflict(
                $column,
                $resource->id,
                $data['starts_at'],
                $data['ends_at'],
                $resource->name,
                $data['reservation_id'] ?? null
            );
        }

        /*
         * Create reservation rows.
         *
         * We keep the created reservations so we can send the
         * correct notifications only after the transaction succeeds.
         */
        $createdReservations = DB::transaction(function () use (
            $request,
            $reservationData
        ) {
            $reservations = collect();

            foreach ($reservationData as $data) {
                $reservation = $this->createReservation(
                    $request,
                    $data
                );

                $reservations->push($reservation);
            }

            return $reservations;
        });

        /*
         * Send notifications for every newly created reservation.
         *
         * Coordinator:
         * - receives every new Pending Reservation.
         *
         * Building Coordinator:
         * - receives Room / Training Media reservations only
         *   for their assigned building.
         *
         * Field Coordinator:
         * - receives Field reservations.
         */
        foreach ($createdReservations as $reservation) {
            $this->sendReservationNotifications(
                $reservation
            );
        }

        /*
         * Clear temporary session data.
         */
        $request->session()->forget([
            'training_officer_reservations',
            'training_officer_cart',
        ]);

        return redirect()
            ->route('training-officer.home')
            ->with(
                'success',
                'Your reservation requests have been submitted successfully.'
            );
    }

    /**
     * Send in-app notifications for one new reservation.
     */
    private function sendReservationNotifications(
        Reservation $reservation
    ): void {
        $recipients = collect();

        /*
         * Coordinator receives every Pending Reservation.
         */
        $coordinators = User::query()
            ->whereHas(
                'role',
                function ($query) {
                    $query->where(
                        'name',
                        'Coordinator'
                    );
                }
            )
            ->get();

        $recipients = $recipients->merge(
            $coordinators
        );

        /*
         * Room / Training Media:
         * notify only Building Coordinators assigned to
         * the resource's building.
         */
        if ($reservation->room_id !== null) {
            $buildingId = $reservation->room?->building_id;

            if ($buildingId !== null) {
                $buildingCoordinators = User::query()
                    ->whereHas(
                        'role',
                        function ($query) {
                            $query->where(
                                'name',
                                'Building Coordinator'
                            );
                        }
                    )
                    ->whereHas(
                        'buildings',
                        function ($query) use (
                            $buildingId
                        ) {
                            $query->where(
                                'buildings.id',
                                $buildingId
                            );
                        }
                    )
                    ->get();

                $recipients = $recipients->merge(
                    $buildingCoordinators
                );
            }
        } elseif ($reservation->training_room_id !== null) {
            $buildingId =
                $reservation->trainingRoom?->building_id;

            if ($buildingId !== null) {
                $buildingCoordinators = User::query()
                    ->whereHas(
                        'role',
                        function ($query) {
                            $query->where(
                                'name',
                                'Building Coordinator'
                            );
                        }
                    )
                    ->whereHas(
                        'buildings',
                        function ($query) use (
                            $buildingId
                        ) {
                            $query->where(
                                'buildings.id',
                                $buildingId
                            );
                        }
                    )
                    ->get();

                $recipients = $recipients->merge(
                    $buildingCoordinators
                );
            }
        }

        /*
         * Field:
         * notify all Field Coordinators.
         */
        if ($reservation->field_id !== null) {
            $fieldCoordinators = User::query()
                ->whereHas(
                    'role',
                    function ($query) {
                        $query->where(
                            'name',
                            'Field Coordinator'
                        );
                    }
                )
                ->get();

            $recipients = $recipients->merge(
                $fieldCoordinators
            );
        }

        /*
         * Avoid duplicate notifications when one user somehow
         * matches more than one recipient group.
         */
        $recipients
            ->unique('id')
            ->each(
                function (User $recipient) use (
                    $reservation
                ) {
                    $resourceLabel = match (true) {
                        $reservation->room_id !== null =>
                            'Room ' . $reservation->room?->name,

                        $reservation->training_room_id !== null =>
                            'Training Media '
                            . $reservation->trainingRoom?->name,

                        $reservation->field_id !== null =>
                            'Field ' . $reservation->field?->name,

                        default => 'the requested resource',
                    };

                    $bookerName = trim((string) $reservation->booker_name);

                    if ($bookerName === '') {
                        $bookerName =
                            $reservation->user?->name
                            ?? 'A user';
                    }

                    Notification::create([
                        'user_id' => $recipient->id,

                        'type' => 'RESERVATION_PENDING',

                        'title' => 'New Reservation Request',

                        'message' =>
                            $bookerName
                            . ' has requested '
                            . $resourceLabel
                            . '. Your approval is required.',

                        'target_type' => 'reservation',

                        'target_id' => $reservation->id,

                        'read_at' => null,
                    ]);
                }
            );
    }

    /**
     * Get all resources from the current cart.
     */
    private function getCartResources(Request $request)
    {
        $cart = $request->session()->get(
            'training_officer_cart',
            []
        );

        $resources = collect();

        /*
         * Rooms
         */
        foreach ($cart['rooms'] ?? [] as $id) {
            $room = Room::with([
                'building',
                'images',
            ])
                ->where('status', 'AVAILABLE')
                ->find((int) $id);

            if ($room) {
                $resources->push([
                    'type' => 'room',
                    'column' => 'room_id',
                    'id' => $room->id,
                    'name' => $room->name,
                    'capacity' => $room->capacity,
                    'building' => $room->building?->name
                        ?? 'Building',
                    'model' => $room,
                ]);
            }
        }

        /*
         * Training Media
         */
        foreach ($cart['training_rooms'] ?? [] as $id) {
            $trainingRoom = TrainingRoom::with([
                'building',
                'images',
            ])
                ->where('status', 'AVAILABLE')
                ->find((int) $id);

            if ($trainingRoom) {
                $resources->push([
                    'type' => 'training_room',
                    'column' => 'training_room_id',
                    'id' => $trainingRoom->id,
                    'name' => $trainingRoom->name,
                    'capacity' => $trainingRoom->capacity,
                    'building' => $trainingRoom->building?->name
                        ?? 'Building',
                    'model' => $trainingRoom,
                ]);
            }
        }

        /*
         * Fields
         */
        foreach ($cart['fields'] ?? [] as $id) {
            $field = Field::with('images')
                ->where('status', 'AVAILABLE')
                ->find((int) $id);

            if ($field) {
                $resources->push([
                    'type' => 'field',
                    'column' => 'field_id',
                    'id' => $field->id,
                    'name' => $field->name,
                    'capacity' => $field->capacity,
                    'building' => 'GITC Facility',
                    'model' => $field,
                ]);
            }
        }

        return $resources->values();
    }

    /**
     * Find resource by type and ID.
     */
    private function findResource(
        string $type,
        int $id
    ): Room|TrainingRoom|Field|null {
        return match ($type) {
            'room' => Room::find($id),

            'training_room' => TrainingRoom::find($id),

            'field' => Field::find($id),

            default => null,
        };
    }

    /**
     * Create one reservation row.
     */
    private function createReservation(
        Request $request,
        array $data
    ): Reservation {
        return Reservation::create([
            'reservation_number' => $this->generateReservationNumber(),

            'user_id' => $request->user()->id,

            'room_id' => $data['resource_type'] === 'room'
                ? $data['resource_id']
                : null,

            'training_room_id' => $data['resource_type'] === 'training_room'
                ? $data['resource_id']
                : null,

            'field_id' => $data['resource_type'] === 'field'
                ? $data['resource_id']
                : null,

            'starts_at' => $data['starts_at'],

            'ends_at' => $data['ends_at'],

            'total_person' => $data['total_person'],

            'event_name' => $data['event_name'],

            'booker_name' => $data['booker_name'],

            'instructor' => $data['instructor'] ?? null,

            'description' => $data['description'],

            'status' => 'PENDING',

            'rejection_reason' => null,
        ]);
    }

    /**
     * Generate unique reservation number.
     */
    private function generateReservationNumber(): string
    {
        do {
            $number = 'GITC-'
                . now()->format('Ymd')
                . '-'
                . strtoupper(Str::random(6));
        } while (
            Reservation::query()
                ->where('reservation_number', $number)
                ->exists()
        );

        return $number;
    }

    /**
     * Validate capacity.
     */
    private function validateCapacity(
        int $totalPerson,
        ?int $capacity,
        string $resourceName
    ): void {
        if (
            $capacity !== null
            && $totalPerson > $capacity
        ) {
            throw ValidationException::withMessages([
                'total_person' => sprintf(
                    '%s has a maximum capacity of %d people.',
                    $resourceName,
                    $capacity
                ),
            ]);
        }
    }

    /**
     * Validate schedule conflict.
     *
     * When editing an existing session reservation,
     * exclude that reservation from the conflict check.
     */
    private function validateScheduleConflict(
        string $column,
        int $resourceId,
        string $startsAt,
        string $endsAt,
        string $resourceName,
        ?int $ignoreReservationId = null
    ): void {
        $query = Reservation::query()
            ->where($column, $resourceId)
            ->whereIn('status', [
                'PENDING',
                'APPROVED',
            ])
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

        if ($ignoreReservationId) {
            $query->where(
                'id',
                '!=',
                $ignoreReservationId
            );
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'starts_at' => sprintf(
                    '%s is already reserved or awaiting approval during the selected time.',
                    $resourceName
                ),
            ]);
        }
    }
}
