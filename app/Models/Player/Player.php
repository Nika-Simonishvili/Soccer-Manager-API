<?php

namespace App\Models\Player;

use App\Models\Country;
use App\Models\Team;
use Database\Factories\PlayerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'team_id',
    'first_name',
    'last_name',
    'age',
    'value',
    'country_id',
])]
#[UseFactory(PlayerFactory::class)]
class Player extends Model
{
    use HasFactory, SoftDeletes;

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(PlayerPosition::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    // public function marketplace(): HasOne
    // {
    //     return $this->hasOne(Marketplace::class);
    // }
}
