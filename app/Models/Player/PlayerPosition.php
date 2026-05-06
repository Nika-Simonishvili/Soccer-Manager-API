<?php

namespace App\Models\Player;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable('name')]
class PlayerPosition extends Model
{
    use HasFactory, SoftDeletes;
}
