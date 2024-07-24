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

    public function stations(): BelongsToMany
    {
        return $this
            ->belongsToMany(Station::class)
            // NOTE: the entire app assumes that each trip has exactly 2 stations.
            // so we might as well assert that here
            ->limit(2);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }
}
