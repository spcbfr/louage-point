<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Station extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function routes(): BelongsToMany
    {
        return $this->belongsToMany(Route::class);
    }
}
