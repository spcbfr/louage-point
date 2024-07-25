<?php

namespace App\Livewire;

use App\Models\Route;
use App\Models\Station;
use App\Models\Trip;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class LogTrips extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('from')
                    ->native(false)
                    ->options(fn () => Station::get()->pluck('name', 'id')),
                Select::make('to')
                    ->native(false)
                    ->different('from')
                    ->options(fn () => Station::get()->pluck('name', 'id')),
                TextInput::make('fare')
                    ->suffix('TND')
                    ->required()
                    ->columnSpanFull()
                    ->minValue(0.900)
                    ->maxValue(80)
                    ->numeric(),
            ])->columns(2)
            ->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();
        $stations = [$data['from'], $data['to']];
        $route = Route::query()
            ->ofStations($stations)
            ->firstOr(function () use ($stations) {
                $new = (new Route);
                $new->save();
                $new->stations()->sync($stations);

                return $new;
            })->load('stations');
        $trip = new Trip;
        $trip->fare = $data['fare'];
        $trip->route_id = $route->id;
        $trip->user_id = auth()->id();
        $trip->save();
        $trip->load('route');
        dd($trip);
    }

    public function render(): View
    {
        return view('livewire.log-trips');
    }
}
