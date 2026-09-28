<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Room;
use App\Models\TrainingRoom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrainingOfficerCartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $this->getCart($request);

        $rooms = collect($cart['rooms'])
            ->map(fn ($id) => Room::with(['building', 'images'])->find($id))
            ->filter();

        $trainingRooms = collect($cart['training_rooms'])
            ->map(fn ($id) => TrainingRoom::with(['building', 'images'])->find($id))
            ->filter();

        $fields = collect($cart['fields'])
            ->map(fn ($id) => Field::with('images')->find($id))
            ->filter();

        return view('training-officer.cart', [
            'rooms' => $rooms,
            'trainingRooms' => $trainingRooms,
            'fields' => $fields,
        ]);
    }

    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:room,training_room,field'],
            'id' => ['required', 'integer'],
        ]);

        $type = $validated['type'];
        $id = (int) $validated['id'];

        $model = match ($type) {
            'room' => Room::query()
                ->where('status', 'AVAILABLE')
                ->findOrFail($id),

            'training_room' => TrainingRoom::query()
                ->where('status', 'AVAILABLE')
                ->findOrFail($id),

            'field' => Field::query()
                ->where('status', 'AVAILABLE')
                ->findOrFail($id),
        };

        $cart = $this->getCart($request);

        $key = $this->getCartKey($type);

        if (! in_array($model->id, $cart[$key], true)) {
            $cart[$key][] = $model->id;
        }

        $request->session()->put(
            'training_officer_cart',
            $cart
        );

        $cartCount = $this->getCartCount($cart);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$model->name} has been added to your reservation cart.",
                'cart_count' => $cartCount,
                'type' => $type,
                'id' => $model->id,
            ]);
        }

        return back()->with(
            'success',
            "{$model->name} has been added to your reservation cart."
        );
    }

    public function remove(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:room,training_room,field'],
            'id' => ['required', 'integer'],
        ]);

        $type = $validated['type'];
        $id = (int) $validated['id'];

        $cart = $this->getCart($request);

        $key = $this->getCartKey($type);

        $cart[$key] = array_values(
            array_filter(
                $cart[$key],
                fn ($itemId) => (int) $itemId !== $id
            )
        );

        $request->session()->put(
            'training_officer_cart',
            $cart
        );

        $cartCount = $this->getCartCount($cart);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Resource removed from your reservation cart.',
                'cart_count' => $cartCount,
                'type' => $type,
                'id' => $id,
            ]);
        }

        return back()->with(
            'success',
            'Resource removed from your reservation cart.'
        );
    }

    private function getCart(Request $request): array
    {
        return array_merge(
            [
                'rooms' => [],
                'training_rooms' => [],
                'fields' => [],
            ],
            $request->session()->get(
                'training_officer_cart',
                []
            )
        );
    }

    private function getCartKey(string $type): string
    {
        return match ($type) {
            'room' => 'rooms',
            'training_room' => 'training_rooms',
            'field' => 'fields',
        };
    }

    private function getCartCount(array $cart): int
    {
        return count($cart['rooms'])
            + count($cart['training_rooms'])
            + count($cart['fields']);
    }
}