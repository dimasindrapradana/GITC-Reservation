<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Carbon\Carbon;
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
     * The public landing page only exposes APPROVED reservations.
     */
    public function reservations(Request $request): JsonResponse
    {
        $request->validate([
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
        ]);

        $timezone = config('app.timezone', 'Asia/Jakarta');

        $monthStart = Carbon::createFromDate(
            (int) $request->year,
            (int) $request->month,
            1,
            $timezone
        )->startOfMonth();

        $monthEnd = $monthStart->copy()->endOfMonth();

        /*
         * A reservation belongs to the month when its time range
         * overlaps that month.
         *
         * This also handles reservations crossing midnight.
         */
        $reservations = Reservation::query()
            ->where('status', 'APPROVED')
            ->where('starts_at', '<', $monthEnd->copy()->addSecond())
            ->where('ends_at', '>=', $monthStart)
            ->with([
                'user',
                'room.building',
                'trainingRoom.building',
                'field',
            ])
            ->orderBy('starts_at')
            ->get();

        $data = $reservations->map(function (Reservation $reservation) use ($timezone) {

            $startsAt = Carbon::parse(
                $reservation->starts_at
            )->setTimezone($timezone);

            $endsAt = Carbon::parse(
                $reservation->ends_at
            )->setTimezone($timezone);

            /*
             * Resolve resource information.
             */
            if ($reservation->room) {

                $resourceType = 'Room';
                $resourceName = $reservation->room->name;

                $buildingName =
                    $reservation->room->building?->name
                    ?? 'Unknown Building';

            } elseif ($reservation->trainingRoom) {

                $resourceType = 'Training Media';
                $resourceName = $reservation->trainingRoom->name;

                $buildingName =
                    $reservation->trainingRoom->building?->name
                    ?? 'Unknown Building';

            } elseif ($reservation->field) {

                $resourceType = 'Field';
                $resourceName = $reservation->field->name;

                $buildingName = 'GITC Facility';

            } else {

                $resourceType = 'Resource';
                $resourceName = 'Unknown Resource';
                $buildingName = 'Unknown Location';
            }

            return [
                'id' => $reservation->id,

                'reservation_number' =>
                    $reservation->reservation_number,

                'event_name' =>
                    $reservation->event_name,

                /*
                 * Booker information.
                 */
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
                    $startsAt->format('Y-m-d H:i'),

                'ends_at' =>
                    $endsAt->format('Y-m-d H:i'),

                'start_date' =>
                    $startsAt->format('Y-m-d'),

                'end_date' =>
                    $endsAt->format('Y-m-d'),

                'start_time' =>
                    $startsAt->format('H:i'),

                'end_time' =>
                    $endsAt->format('H:i'),

                'status' =>
                    'APPROVED',
            ];
        });

        return response()->json([
            'year' => (int) $request->year,
            'month' => (int) $request->month,
            'reservations' => $data->values(),
        ]);
    }
}