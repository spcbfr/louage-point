<?php

use App\Livewire\LogTrips;
use App\Models\Route;
use App\Models\Station;
use App\Models\Trip;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(LogTrips::class)
        ->assertStatus(200);
});
it('creates trip with new route if it doesn\'t already exist', function () {
    $user = User::factory()->create();
    Station::factory(3)->create();
    Livewire::actingAs($user)
        ->test(LogTrips::class)
        ->fillForm([
            'from' => 1,
            'to' => 2,
            'fare' => 20,
        ])
        ->call('create');

    $route = Route::query()
        ->whereHas('stations', fn ($query) => $query->where('station_id', 1))
        ->whereHas('stations', fn ($query) => $query->where('station_id', 2))
        ->sole();

    $trip = Trip::query()
        ->where('user_id', $user->id)
        ->where('route_id', $route->id)
        ->first();

    expect($trip->fare)
        ->toBe(20);
});

it('creates trip with existing route if it already exist', function () {
    $user = User::factory()->create();
    Station::factory(3)->create();

    $route = new Route;
    $route->save();
    $route->stations()->sync([1, 2]);

    Livewire::actingAs($user)
        ->test(LogTrips::class)
        ->fillForm([
            'from' => 1,
            'to' => 2,
            'fare' => 20,
        ])
        ->call('create');

    $queriedRoute = Route::query()
        ->whereHas('stations', fn ($query) => $query->where('station_id', 1))
        ->whereHas('stations', fn ($query) => $query->where('station_id', 2));

    expect($queriedRoute->count())
        ->toBe(1);

    expect($queriedRoute->first()->id)
        ->toBe($route->id);

    $trip = Trip::query()
        ->where('user_id', $user->id)
        ->where('route_id', $route->id)
        ->first();

    expect($trip->fare)
        ->toBe(20);
});
