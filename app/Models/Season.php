<?php

namespace App\Models;

use Database\Factories\SeasonFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $colour
 * @property string|null $description
 */
#[Fillable(['name', 'description', 'colour'])]
class Season extends Model
{
    /** @use HasFactory<SeasonFactory> */
    use HasFactory;

    /** @return HasMany<Show, $this> */
    public function shows(): HasMany
    {
        return $this->hasMany(Show::class);
    }
}
