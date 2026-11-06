<?php

namespace App\Models;

use Database\Factories\YearFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 */
#[Fillable(['name', 'description'])]
class Year extends Model
{
    /** @use HasFactory<YearFactory> */
    use HasFactory;

    /** @return HasMany<Show, $this> */
    public function shows(): HasMany
    {
        return $this->hasMany(Show::class);
    }
}
