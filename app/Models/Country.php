<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable(['name', 'code'])]
#[Translatable('name')]
class Country extends Model
{
    use HasFactory, HasTranslations;

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }
}
