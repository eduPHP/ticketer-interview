<?php

use App\Models\Event;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

test('it creates a reservation', function () {
    $event = Event::factory()->create();
    $user = User::factory()->create();

    $reservation = $user->reserveFor($event);

    expect($reservation)->not->toBeNull();
    expect($reservation::class)->toBe(Reservation::class);
    assertDatabaseHas('reservations', [
        'user_id' => $user->id,
        'event_id' => $event->id,
     ]);
});

test('it does not create a reservation when capacity is full', function () {
    $existingReservation = Reservation::factory()->create([
        'event_id' => Event::factory()->create(['capacity' => 1]),
    ]);
    $event = $existingReservation->event;

    $user = User::factory()->create();

    try {
        $user->reserveFor($event);
        $this->fail('should have failed reservation due to capacity full');
    } catch (Exception $exception) {
        expect($exception->getMessage())->toBe('Event is full');
    }

    assertDatabaseMissing('reservations', [
        'user_id' => $user->id,
        'event_id' => $event->id,
     ]);
});

test('it creates a reservation through endpoint', function () {
    $event = Event::factory()->create();
    $user = User::factory()->create();

    $reserve = $this->actingAs($user)->post(route('reservations.create', $event));

    $reserve->assertOk();

    expect($reserve->json('ticket'))->toBe('TICKET0001');

    $this->assertDatabaseHas('reservations', [
        'user_id' => $user->id,
        'event_id' => $event->id,
     ]);
});
