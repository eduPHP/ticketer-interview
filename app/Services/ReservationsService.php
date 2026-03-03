<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReservationsService
{
    public function reserve(Event $event, User $user): ?Reservation
    {
        $reservation = null;

        DB::transaction(function () use ($event, $user, &$reservation) {
            $updated = Event::whereKey($event->id)
                ->whereColumn('reservations_count', '<', 'capacity')
                ->increment('reservations_count');

            if ($updated === 0) {
                throw new \Exception('Event is full');
            }

            $reservation = $user->reserveFor($event);
        });

        return $reservation;
    }
}
