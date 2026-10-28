<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\TrainingSession;
use Carbon\CarbonInterface;

class TrainingScheduleConflictService
{
    /**
     * Check whether a Training Media has an overlapping reservation
     * or another training session.
     */
    public function hasConflict(
        int $trainingRoomId,
        CarbonInterface $start,
        CarbonInterface $end,
        ?int $ignoreSessionId = null
    ): bool {
        if ($end->lessThanOrEqualTo($start)) {
            return true;
        }

        $reservationConflict = Reservation::query()
            ->where('training_room_id', $trainingRoomId)
            ->where('status', 'APPROVED')
            ->where('starts_at', '<', $end)
            ->where('ends_at', '>', $start)
            ->exists();

        if ($reservationConflict) {
            return true;
        }

        $sessionQuery = TrainingSession::query()
            ->where('training_room_id', $trainingRoomId)
            ->where('session_date', $start->toDateString())
            ->where('start_time', '<', $end->format('H:i:s'))
            ->where('end_time', '>', $start->format('H:i:s'));

        if ($ignoreSessionId !== null) {
            $sessionQuery->where('id', '!=', $ignoreSessionId);
        }

        return $sessionQuery->exists();
    }
}
