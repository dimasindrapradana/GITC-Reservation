<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TrainingOfficerClassroomReservationController extends Controller
{
    /**
     * Session key used by the Training Officer Classroom cart.
     */
    private const CART_SESSION_KEY = 'training_officer_classroom_cart';

    /**
     * Session key used to temporarily store
     * classroom reservation form data.
     */
    private const RESERVATION_SESSION_KEY =
        'training_officer_classroom_reservations';

    public function myReservations(Request $request): View
    {
        $reservations = Reservation::query()
            ->with([
                'room.building',
            ])
            ->where('user_id', auth()->id())
            ->whereNotNull('room_id')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view(
            'training-officer-classroom.reservation.my-index',
            [
                'reservations' => $reservations,
            ]
        );
    }

    /**
     * Show one reservation detail.
     */
    public function show(
        Request $request,
        Reservation $reservation
    ): View|RedirectResponse {
        /*
         * Only allow the logged-in Training Officer
         * to view their own reservation.
         */
        if ((int) $reservation->user_id !== (int) $request->user()->id) {
            abort(403);
        }

        /*
         * Load related room and building data.
         */
        $reservation->load([
            'room.building',
        ]);

        return view(
            'training-officer-classroom.reservation.my-show',
            [
                'reservation' => $reservation,
            ]
        );
    }

    /**
     * Start classroom reservation process.
     */
    public function create(Request $request): RedirectResponse
    {
        $resources = $this->getCartResources($request);

        if ($resources->isEmpty()) {
            return redirect()
                ->route('training-officer.classroom.cart')
                ->with(
                    'error',
                    'Your booking list is empty.'
                );
        }

        $firstResource = $resources->first();

        return redirect()->route(
            'training-officer.classroom.reservation.resource.create',
            [
                'room' => $firstResource['id'],
            ]
        );
    }

    /**
     * Show reservation form for one classroom room.
     */
    public function createResource(
        Request $request,
        int $room
    ): View|RedirectResponse {
        $resources = $this->getCartResources($request);

        if ($resources->isEmpty()) {
            return redirect()
                ->route('training-officer.classroom.cart')
                ->with(
                    'error',
                    'Your booking list is empty.'
                );
        }

        /*
         * Find the current room inside the cart.
         */
        $currentIndex = $resources->search(
            fn ($item) =>
                (int) $item['id'] === $room
        );

        if ($currentIndex === false) {
            return redirect()
                ->route(
                    'training-officer.classroom.reservation.create'
                )
                ->with(
                    'error',
                    'The selected room is not in your booking list.'
                );
        }

        $selectedResource = $resources->get($currentIndex);

        /*
         * Get temporary reservation data.
         */
        $reservationData = $request->session()->get(
            self::RESERVATION_SESSION_KEY,
            []
        );

        $currentData = $reservationData[$currentIndex] ?? [];

        $isEdit = $request->boolean('edit');

        return view(
            'training-officer-classroom.reservation.create',
            [
                'resource' => $selectedResource,

                'room' => $selectedResource['model'],

                'roomId' => $selectedResource['id'],

                'step' => $currentIndex + 1,

                'totalSteps' => $resources->count(),

                'currentData' => $currentData,

                'isEdit' => $isEdit,
            ]
        );
    }

    /**
     * Store reservation data for one classroom room.
     */
    public function storeResource(
        Request $request,
        int $room
    ): RedirectResponse {
        $resources = $this->getCartResources($request);

        if ($resources->isEmpty()) {
            return redirect()
                ->route('training-officer.classroom.cart')
                ->with(
                    'error',
                    'Your booking list is empty.'
                );
        }

        /*
         * Find current room in cart.
         */
        $currentIndex = $resources->search(
            fn ($item) =>
                (int) $item['id'] === $room
        );

        if ($currentIndex === false) {
            return redirect()
                ->route(
                    'training-officer.classroom.reservation.create'
                )
                ->with(
                    'error',
                    'The selected room is not in your booking list.'
                );
        }

        $selectedResource = $resources->get($currentIndex);

        /*
         * Validate reservation form.
         */
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
                'max:255',
            ],

            'description' => [
                'required',
                'string',
            ],
        ]);

        /*
         * Validate room capacity.
         */
        $this->validateCapacity(
            (int) $validated['total_person'],
            $selectedResource['capacity'],
            $selectedResource['name']
        );

        /*
         * Get current temporary reservation data.
         */
        $reservationData = $request->session()->get(
            self::RESERVATION_SESSION_KEY,
            []
        );

        /*
         * If editing an existing reservation entry,
         * ignore its reservation ID during conflict checking.
         */
        $ignoreReservationId = null;

        if ($request->boolean('edit')) {
            $ignoreReservationId =
                $reservationData[$currentIndex]['reservation_id']
                ?? null;
        }

        /*
         * Validate schedule conflict.
         */
        $this->validateScheduleConflict(
            (int) $selectedResource['id'],
            $validated['starts_at'],
            $validated['ends_at'],
            $selectedResource['name'],
            $ignoreReservationId
        );

        /*
         * Preserve existing reservation ID
         * when editing.
         */
        $existingData =
            $reservationData[$currentIndex] ?? [];

        /*
         * Save reservation data into session.
         */
        $reservationData[$currentIndex] = [
            'reservation_id' =>
                $existingData['reservation_id']
                ?? null,

            'resource_type' =>
                'room',

            'resource_id' =>
                (int) $selectedResource['id'],

            'resource_name' =>
                $selectedResource['name'],

            'building' =>
                $selectedResource['building'],

            'capacity' =>
                $selectedResource['capacity'],

            'starts_at' =>
                $validated['starts_at'],

            'ends_at' =>
                $validated['ends_at'],

            'total_person' =>
                (int) $validated['total_person'],

            'event_name' =>
                $validated['event_name'],

            'booker_name' =>
                $validated['booker_name'],

            'instructor' =>
                $validated['instructor'] ?? null,

            'description' =>
                $validated['description'],
        ];

        /*
         * Save temporary reservation data.
         */
        $request->session()->put(
            self::RESERVATION_SESSION_KEY,
            $reservationData
        );

        /*
         * If editing from review,
         * return directly to review.
         */
        if ($request->boolean('edit')) {
            return redirect()
                ->route(
                    'training-officer.classroom.reservation.review'
                )
                ->with(
                    'success',
                    'Reservation details updated.'
                );
        }

        /*
         * Find next room.
         */
        $nextResource =
            $resources->get($currentIndex + 1);

        /*
         * Continue to next room.
         */
        if ($nextResource) {
            return redirect()->route(
                'training-officer.classroom.reservation.resource.create',
                [
                    'room' => $nextResource['id'],
                ]
            );
        }

        /*
         * All rooms completed.
         */
        return redirect()
            ->route(
                'training-officer.classroom.reservation.review'
            );
    }

    /**
     * Show reservation review page.
     */
    public function review(
        Request $request
    ): View|RedirectResponse {
        $resources = $this->getCartResources($request);

        if ($resources->isEmpty()) {
            return redirect()
                ->route(
                    'training-officer.classroom.cart'
                )
                ->with(
                    'error',
                    'Your booking list is empty.'
                );
        }

        /*
         * Get temporary reservation data.
         */
        $reservationData = $request->session()->get(
            self::RESERVATION_SESSION_KEY,
            []
        );

        /*
         * Make sure every room has
         * completed reservation data.
         */
        if (
            count($reservationData)
            !== $resources->count()
        ) {
            return redirect()
                ->route(
                    'training-officer.classroom.reservation.create'
                )
                ->with(
                    'error',
                    'Please complete all classroom reservation forms before reviewing.'
                );
        }

        /*
         * Make sure reservation indexes
         * are complete and ordered.
         */
        foreach ($resources as $index => $resource) {
            if (
                !isset($reservationData[$index])
                ||
                (int) ($reservationData[$index]['resource_id'] ?? 0)
                    !== (int) $resource['id']
            ) {
                return redirect()
                    ->route(
                        'training-officer.classroom.reservation.create'
                    )
                    ->with(
                        'error',
                        'Please complete all classroom reservation forms before reviewing.'
                    );
            }
        }

        return view(
            'training-officer-classroom.reservation.review',
            [
                'reservations' =>
                    $reservationData,

                'totalItems' =>
                    $resources->count(),
            ]
        );
    }

    /**
     * Final reservation submission.
     */
    public function submit(
        Request $request
    ): RedirectResponse {
        $resources = $this->getCartResources($request);

        if ($resources->isEmpty()) {
            return redirect()
                ->route(
                    'training-officer.classroom.cart'
                )
                ->with(
                    'error',
                    'Your booking list is empty.'
                );
        }

        /*
         * Get temporary reservation data.
         */
        $reservationData = $request->session()->get(
            self::RESERVATION_SESSION_KEY,
            []
        );

        /*
         * Make sure every room has
         * completed reservation data.
         */
        if (
            count($reservationData)
            !== $resources->count()
        ) {
            return redirect()
                ->route(
                    'training-officer.classroom.reservation.create'
                )
                ->with(
                    'error',
                    'Please complete all classroom reservation forms before submitting.'
                );
        }

        /*
         * Re-check every room before creating
         * permanent reservation records.
         */
        foreach ($reservationData as $data) {
            $roomId = (int) ($data['resource_id'] ?? 0);

            $room = Room::query()
                ->where('id', $roomId)
                ->first();

            /*
             * Room must still exist and be available.
             */
            if (
                !$room
                ||
                $room->status !== 'AVAILABLE'
            ) {
                return redirect()
                    ->route(
                        'training-officer.classroom.cart'
                    )
                    ->with(
                        'error',
                        ($data['resource_name'] ?? 'The selected room')
                        . ' is no longer available.'
                    );
            }

            /*
             * Capacity check.
             */
            $this->validateCapacity(
                (int) $data['total_person'],
                $room->capacity,
                $room->name
            );

            /*
             * Schedule conflict check.
             */
            $this->validateScheduleConflict(
                $room->id,
                $data['starts_at'],
                $data['ends_at'],
                $room->name,
                $data['reservation_id'] ?? null
            );
        }

        /*
         * Create all reservation records
         * inside one database transaction.
         *
         * Keep the created reservations so notifications
         * can be sent only after the transaction succeeds.
         */
        $createdReservations = DB::transaction(
            function () use (
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
            }
        );

        /*
         * Send notifications for every newly created classroom reservation.
         *
         * Coordinator:
         * - receives every new Pending Reservation.
         *
         * Building Coordinator:
         * - receives Room reservations only for their assigned building.
         */
        foreach ($createdReservations as $reservation) {
            $this->sendReservationNotifications(
                $reservation
            );
        }

        /*
         * Clear temporary classroom
         * reservation session data.
         */
        $request->session()->forget([
            self::RESERVATION_SESSION_KEY,
            self::CART_SESSION_KEY,
        ]);

        /*
         * Return to classroom home.
         */
        return redirect()
            ->route(
                'training-officer.classroom.home'
            )
            ->with(
                'success',
                'Your classroom reservation requests have been submitted successfully.'
            );
    }

    /**
     * Send in-app notifications for one new classroom reservation.
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
         * Classroom Room:
         * notify only Building Coordinators assigned to
         * the room's building.
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
        }

        /*
         * Avoid duplicate notifications.
         */
        $recipients
            ->unique('id')
            ->each(
                function (User $recipient) use (
                    $reservation
                ) {
                    $bookerName =
                        $reservation->booker_name
                        ?: $reservation->user?->name
                        ?: 'A user';

                    $roomName =
                        $reservation->room?->name
                        ?: 'the selected room';

                    Notification::create([
                        'user_id' =>
                            $recipient->id,

                        'type' =>
                            'RESERVATION_PENDING',

                        'title' =>
                            'New Reservation Request',

                        'message' =>
                            $bookerName
                            . ' has requested '
                            . 'Room '
                            . $roomName
                            . '. Your approval is required.',

                        'target_type' =>
                            'reservation',

                        'target_id' =>
                            $reservation->id,

                        'read_at' =>
                            null,
                    ]);
                }
            );
    }

    /**
     * Get all rooms from classroom booking cart.
     */
    private function getCartResources(
        Request $request
    ) {
        $cart = $request->session()->get(
            self::CART_SESSION_KEY,
            [
                'rooms' => [],
                'training_rooms' => [],
                'fields' => [],
            ]
        );

        /*
         * Get room IDs only.
         */
        $roomIds = collect(
            $cart['rooms'] ?? []
        )
            ->map(
                fn ($id) => (int) $id
            )
            ->filter(
                fn ($id) => $id > 0
            )
            ->values();

        if ($roomIds->isEmpty()) {
            return collect();
        }

        /*
         * Get rooms from database.
         *
         * Do not silently remove unavailable rooms
         * from the workflow here.
         *
         * If a room becomes unavailable after
         * being added to the cart, the reservation
         * form can detect it properly.
         */
        $rooms = Room::query()
            ->with([
                'building',
                'images',
            ])
            ->whereIn(
                'id',
                $roomIds
            )
            ->get();

        /*
         * Preserve cart order.
         */
        return $roomIds
            ->map(
                function ($id) use ($rooms) {
                    $room = $rooms->firstWhere(
                        'id',
                        $id
                    );

                    if (!$room) {
                        return null;
                    }

                    return [
                        'type' =>
                            'room',

                        'column' =>
                            'room_id',

                        'id' =>
                            (int) $room->id,

                        'name' =>
                            $room->name,

                        'capacity' =>
                            $room->capacity,

                        'building' =>
                            $room->building?->name
                            ?? 'Building',

                        'model' =>
                            $room,
                    ];
                }
            )
            ->filter()
            ->values();
    }

    /**
     * Create one reservation row.
     */
    private function createReservation(
        Request $request,
        array $data
    ): Reservation {
        return Reservation::create([
            'reservation_number' =>
                $this->generateReservationNumber(),

            'user_id' =>
                $request->user()->id,

            'room_id' =>
                (int) $data['resource_id'],

            'training_room_id' =>
                null,

            'field_id' =>
                null,

            'starts_at' =>
                $data['starts_at'],

            'ends_at' =>
                $data['ends_at'],

            'total_person' =>
                (int) $data['total_person'],

            'event_name' =>
                $data['event_name'],

            'booker_name' =>
                $data['booker_name'],

            'instructor' =>
                $data['instructor']
                ?? null,

            'description' =>
                $data['description'],

            'status' =>
                'PENDING',

            'rejection_reason' =>
                null,
        ]);
    }

    /**
     * Generate unique reservation number.
     */
    private function generateReservationNumber(): string
    {
        do {
            $number =
                'GITC-'
                . now()->format('Ymd')
                . '-'
                . strtoupper(
                    Str::random(6)
                );
        } while (
            Reservation::query()
                ->where(
                    'reservation_number',
                    $number
                )
                ->exists()
        );

        return $number;
    }

    /**
     * Validate room capacity.
     */
    private function validateCapacity(
        int $totalPerson,
        ?int $capacity,
        string $resourceName
    ): void {
        if (
            $capacity !== null
            &&
            $totalPerson > $capacity
        ) {
            throw ValidationException::withMessages([
                'total_person' =>
                    sprintf(
                        '%s has a maximum capacity of %d people.',
                        $resourceName,
                        $capacity
                    ),
            ]);
        }
    }

    /**
     * Validate room schedule conflict.
     */
    private function validateScheduleConflict(
        int $roomId,
        string $startsAt,
        string $endsAt,
        string $resourceName,
        ?int $ignoreReservationId = null
    ): void {
        $query = Reservation::query()
            ->where(
                'room_id',
                $roomId
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

        /*
         * Ignore current reservation
         * when editing.
         */
        if ($ignoreReservationId) {
            $query->where(
                'id',
                '!=',
                $ignoreReservationId
            );
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'starts_at' =>
                    sprintf(
                        '%s is already reserved or awaiting approval during the selected time.',
                        $resourceName
                    ),
            ]);
        }
    }
}
