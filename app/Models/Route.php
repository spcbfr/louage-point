<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getNameAttribute()
    {
        return $this->stations->pluck('name')->implode('-');
    }

    public function getCalculatedWaitTimeAttribute()
    {
        return $this->trips->avg('wait_time');
    }

    public function scopeOfStations($query, array $nodes)
    {
        //  WARNING: do not replace the multiple whereHas clauses generated below
        //  with a single whereIn, `whereIn` performs an `OR` operation. but
        //  here we need an `AND` operation.
        //  as in: "return all routes that have this exact set of nodes. "
        foreach ($nodes as $node) {
            $query->whereHas('stations', fn ($q) => $q->where('station_id', $node));
        }

        return $query;
    }

    public function stations(): BelongsToMany
    {
        return $this
            ->belongsToMany(Station::class)
            //  NOTE: the entire app assumes that each trip has exactly 2 stations.
            //  so we might as well assert that here
            ->limit(2);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
}
