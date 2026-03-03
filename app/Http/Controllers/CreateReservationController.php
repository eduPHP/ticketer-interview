<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\ReservationsService;

class CreateReservationController
{
    public function __invoke(Event $event, ReservationsService $service)
    {
        $reservation = $service->reserve($event, auth()->user());

        $event->refresh();

        return response()->json([
            'event' => [
                'id' => $event->id,
            ],
            'capacity' => $event->capacity,
            'ticket' => 'TICKET'.str($reservation->id)->padLeft(4, '0'),
        ]);

    }
}
