<?php

namespace App\Models;

use Database\Factories\SeasonFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property Carbon|null $deleted_at
 * @property string $name
 * @property string $colour
 * @property string|null $description
 */
#[Fillable(['name', 'description', 'colour'])]
class Season extends Model
{
    /** @use HasFactory<SeasonFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    /** @return HasMany<Show, $this> */
    public function shows(): HasMany
    {
        return $this->hasMany(Show::class);
    }
}
