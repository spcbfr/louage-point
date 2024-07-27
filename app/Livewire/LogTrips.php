<?php

namespace App\Livewire;

use App\Models\Route;
use App\Models\Station;
use App\Models\Trip;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
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
                Section::make('Essentials')
                    ->icon('heroicon-o-truck')
                    ->iconColor('info')
                    ->schema([
                        Select::make('from_id')
                            ->native(false)
                            ->required()
                            ->searchable()
                            ->label('Departure Station')
                            ->options(fn () => Station::get()->pluck('name', 'id')),
                        Select::make('to_id')
                            ->native(false)
                            ->searchable()
                            ->required()
                            ->different('from_id')
                            ->label('Arrival Station')
                            ->options(fn () => Station::get()->pluck('name', 'id')),
                        TextInput::make('fare')
                            ->suffix('TND')
                            ->required()
                            ->minValue(0.900)
                            ->columnSpanFull()
                            ->maxValue(80)
                            ->numeric(),
                    ])->columns(2),
                Section::make('Additional Info')
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('info')
                    ->description('This information can help us provide better wait time estimates')
                    ->schema([
                        Select::make('wait_time')
                            ->helperText('How long did you wait for the louage to leave?')
                            ->native(false)
                            ->options([
                                0 => 'Left Immidiately',
                                5 => '5 Minutes',
                                10 => '10 Minutes',
                                20 => '20 Minutes',
                                30 => '30 Minutes',
                                45 => '45 Minutes',
                                60 => '1 Hour',
                                90 => '1.5 Hours',
                            ]),
                        ToggleButtons::make('travel_type')
                            // ->hiddenLabel()
                            ->label('I travelled...')
                            ->inline()
                            ->requiredWith('wait_time')
                            ->columnSpan(fn ($get) => $get('travel_type') != 'group' ? 2 : 1)
                            ->options([
                                'alone' => 'alone',
                                'group' => 'with a group',
                            ])
                            ->colors(
                                [
                                    'alone' => 'info',
                                    'group' => 'info',
                                ]
                            )
                            ->icons([
                                'alone' => 'heroicon-o-user',
                                'group' => 'heroicon-o-users',
                            ])
                            ->live(),
                        TextInput::make('group_size')
                            ->requiredIf('group', 'group')
                            ->visible(fn (Get $get) => $get('travel_type') == 'group')
                            ->maxValue(6)
                            ->minValue(2)
                            ->numeric(),
                        TimePicker::make('departured_at')
                            ->seconds(false),
                        Textarea::make('review')
                            ->rows(4)
                            ->columnSpan(2),
                    ])->columns(3),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $data = $this->form->getState();
        $stations = [$data['from_id'], $data['to_id']];
        DB::transaction(function () use ($data, $stations) {
            $route = Route::query()
                ->ofStations($stations)
                ->firstOr(function () use ($stations) {
                    $new = new Route;
                    $new->save();
                    $new->stations()->sync($stations);

                    return $new;
                })->load('stations');
            $tooManyTrips = Trip::where('user_id', auth()->id())
                ->where('route_id', $route->id)
                ->where('created_at', '>', now()->subDays(7))
                ->exists();

            if ($tooManyTrips) {
                $this->addError('from_id', 'you may log only one trip per week and per route');
            }

            $trip = new Trip;
            $trip->fill(Arr::except($data, ['travel_type', 'from_id', 'departured_at', 'to_id']));
            if ($data['departured_at']) {
                $trip->departured_at = now()->setTimeFromTimeString($data['departured_at'].':00');
            }
            $trip->route_id = $route->id;
            $trip->user_id = auth()->id();
            $trip->save();

            $trip->load('route');

            $this->data = [];
        });
    }

    public function render(): View
    {
        return view('livewire.log-trips');
    }
}
