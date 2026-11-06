<?php

namespace App\Models;

use Database\Factories\PlaywrightFactory;
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
 * @property string|null $description
 */
#[Fillable(['name', 'description'])]
class Playwright extends Model
{
    /** @use HasFactory<PlaywrightFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    /** @return HasMany<Show, $this> */
    public function shows(): HasMany
    {
        return $this->hasMany(Show::class);
    }
}
