<?php

namespace App\Models;

use Database\Factories\YearFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
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
 * @property int $year_start
 * @property string|null $description
 */
#[Fillable(['name', 'year_start', 'description'])]
class Year extends Model
{
    /** @use HasFactory<YearFactory> */
    use HasFactory, HasUuids, SoftDeletes;

    protected static function booted(): void
    {
        static::addGlobalScope('year_start', fn (Builder $query): Builder => $query
            ->orderBy($query->qualifyColumn('year_start'))
            ->orderBy($query->qualifyColumn('id')));
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['year_start' => 'integer'];
    }

    /** @return HasMany<Show, $this> */
    public function shows(): HasMany
    {
        return $this->hasMany(Show::class)->orderBy('season_sort');
    }
}
