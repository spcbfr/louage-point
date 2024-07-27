<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Station extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function destinations()
    {
        $routeIds = $this->routes()->pluck('routes.id'); // getting all the routes for a given station, so far so good

        // get all stations where has a route within the ids above.
        $stations = Station::query()
            ->withWhereHas('routes', function ($query) use ($routeIds) {
                $query->whereIn('route_id', $routeIds);
            })
            ->where('id', '!=', $this->id)
            ->get();

        return $stations;

    }

    public function routes(): BelongsToMany
    {
        return $this->belongsToMany(Route::class);
    }
}
