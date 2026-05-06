<?php

namespace App\Models;

use App\Models\Player\Player;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Table('marketplace')]
#[Fillable(['player_id', 'price'])]
class Marketplace extends Model
{
    use HasFactory, SoftDeletes;

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
