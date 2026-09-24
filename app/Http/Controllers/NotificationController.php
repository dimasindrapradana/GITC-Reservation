<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = Notification::query()
            ->where('user_id', auth()->id())
            ->orderByRaw('read_at IS NULL DESC')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('notifications.index', [
            'notifications' => $notifications,
        ]);
    }

    public function read(Notification $notification): RedirectResponse
    {
        abort_unless(
            $notification->user_id === auth()->id(),
            403
        );

        if ($notification->read_at === null) {
            $notification->update([
                'read_at' => now(),
            ]);
        }

        if (
            $notification->target_type === 'reservation' &&
            $notification->target_id !== null
        ) {
            return redirect()->route(
                'coordinator.reservations.show',
                $notification->target_id
            );
        }

        return redirect()->route('notifications.index');
    }

    public function readAll(): RedirectResponse
    {
        Notification::query()
            ->where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        return back()->with(
            'success',
            'All notifications have been marked as read.'
        );
    }
}