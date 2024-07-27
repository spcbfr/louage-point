<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Contracts\View\View;

class StationController extends Controller
{
    public function index(): View
    {
        $stations = Station::query()->get();

        return view('stations', ['stations' => $stations]);
    }

    public function show(Station $station): View
    {
        return view('station', ['station' => $station]);
    }
}
