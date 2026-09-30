<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainingOfficerClassroomCartController extends Controller
{
    /**
     * Session key khusus untuk Training Officer Classroom.
     *
     * Sengaja berbeda dari:
     * training_officer_cart
     *
     * agar cart Training Officer dan Training Officer Classroom
     * tidak saling bercampur.
     */
    private const CART_SESSION_KEY = 'training_officer_classroom_cart';

    /**
     * Default cart structure.
     */
    private function emptyCart(): array
    {
        return [
            'rooms' => [],
        ];
    }

    /**
     * Get cart from session and normalize it.
     */
    private function getCart(Request $request): array
    {
        $cart = $request->session()->get(
            self::CART_SESSION_KEY,
            $this->emptyCart()
        );

        return [
            'rooms' => collect($cart['rooms'] ?? [])
                ->map(fn ($id) => (int) $id)
                ->filter(fn ($id) => $id > 0)
                ->unique()
                ->values()
                ->all(),
        ];
    }

    /**
     * Save cart to session.
     */
    private function saveCart(
        Request $request,
        array $cart
    ): void {
        $request->session()->put(
            self::CART_SESSION_KEY,
            $cart
        );
    }

    /**
     * Show Training Officer Classroom cart.
     */
    public function index(Request $request): View
    {
        $cart = $this->getCart($request);

        $roomIds = collect($cart['rooms'])
            ->map(fn ($id) => (int) $id)
            ->values();

        $rooms = Room::query()
            ->with([
                'building',
                'images',
            ])
            ->whereIn('id', $roomIds)
            ->get()
            ->sortBy(function ($room) use ($roomIds) {

                $position = $roomIds->search(
                    (int) $room->id
                );

                return $position === false
                    ? PHP_INT_MAX
                    : $position;
            })
            ->values();

        return view(
            'training-officer-classroom.cart',
            [
                'rooms' => $rooms,
            ]
        );
    }

    /**
     * Add a room to Training Officer Classroom cart.
     */
    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'id' => [
                'required',
                'integer',
                'exists:rooms,id',
            ],

            'type' => [
                'required',
                'string',
                'in:room',
            ],
        ]);

        $roomId = (int) $validated['id'];

        /*
         * Make sure the room still exists
         * and is currently available.
         */
        $room = Room::query()
            ->where('id', $roomId)
            ->where('status', 'AVAILABLE')
            ->first();

        if (! $room) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This room is currently unavailable.',
                ], 422);
            }

            return back()->with(
                'error',
                'This room is currently unavailable.'
            );
        }

        /*
         * Get Classroom cart.
         */
        $cart = $this->getCart($request);

        /*
         * Prevent duplicate room.
         */
        if (
            in_array(
                $roomId,
                $cart['rooms'],
                true
            )
        ) {
            $cartCount = $this->getCartCount($cart);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This room is already in your booking list.',
                    'cart_count' => $cartCount,
                    'cart' => $this->formatCartForFrontend($cart),
                ], 422);
            }

            return back()->with(
                'error',
                'This room is already in your booking list.'
            );
        }

        /*
         * Add room.
         */
        $cart['rooms'][] = $roomId;

        $cart['rooms'] = collect($cart['rooms'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        /*
         * Save Classroom cart.
         */
        $this->saveCart(
            $request,
            $cart
        );

        $cartCount = $this->getCartCount($cart);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Room added to your booking list.',
                'cart_count' => $cartCount,
                'type' => 'room',
                'id' => $roomId,
                'cart' => $this->formatCartForFrontend($cart),
            ]);
        }

        return back()->with(
            'success',
            'Room added to your booking list.'
        );
    }

    /**
     * Remove a room from Training Officer Classroom cart.
     */
    public function remove(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'id' => [
                'required',
                'integer',
            ],

            'type' => [
                'required',
                'string',
                'in:room',
            ],
        ]);

        $roomId = (int) $validated['id'];

        /*
         * Get Classroom cart.
         */
        $cart = $this->getCart($request);

        /*
         * Remove requested room.
         */
        $cart['rooms'] = collect($cart['rooms'])
            ->reject(
                fn ($id) => (int) $id === $roomId
            )
            ->values()
            ->all();

        /*
         * Save updated Classroom cart.
         */
        $this->saveCart(
            $request,
            $cart
        );

        $cartCount = $this->getCartCount($cart);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Room removed from your booking list.',
                'cart_count' => $cartCount,
                'type' => 'room',
                'id' => $roomId,
                'cart' => $this->formatCartForFrontend($cart),
            ]);
        }

        return back()->with(
            'success',
            'Room removed from your booking list.'
        );
    }

    /**
     * Clear entire Training Officer Classroom cart.
     */
    public function clear(Request $request): JsonResponse|RedirectResponse
    {
        $cart = $this->emptyCart();

        $this->saveCart(
            $request,
            $cart
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your booking list has been cleared.',
                'cart_count' => 0,
                'cart' => [],
            ]);
        }

        return back()->with(
            'success',
            'Your booking list has been cleared.'
        );
    }

    /**
     * Get total number of items in Classroom cart.
     */
    private function getCartCount(array $cart): int
    {
        return count($cart['rooms']);
    }

    /**
     * Format cart for frontend JavaScript.
     */
    private function formatCartForFrontend(
        array $cart
    ): array {
        $items = [];

        foreach ($cart['rooms'] ?? [] as $id) {
            $items[] = [
                'id' => (int) $id,
                'type' => 'room',
            ];
        }

        return array_values($items);
    }
}