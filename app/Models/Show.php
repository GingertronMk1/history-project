<?php

namespace App\Models;

use Database\Factories\ShowFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $year
 * @property int $season_sort
 * @property string|null $description
 * @property int|null $company_id
 * @property-read Company|null $company
 * @property int|null $season_id
 * @property-read Season|null $season
 * @property int|null $period_id
 * @property-read Period|null $period
 * @property int|null $playwright_id
 * @property-read Playwright|null $playwright
 * @property int|null $venue_id
 * @property-read Venue|null $venue
 * @property Carbon|null $date_start
 * @property Carbon|null $date_end
 */
#[Fillable(['title', 'year', 'season_sort', 'description', 'company_id', 'season_id', 'period_id', 'playwright_id', 'venue_id', 'date_start', 'date_end'])]
class Show extends Model
{
    /** @use HasFactory<ShowFactory> */
    use HasFactory;

    /** @return BelongsTo<Company, $this> */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /** @return BelongsTo<Season, $this> */
    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    /** @return BelongsTo<Period, $this> */
    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    /** @return BelongsTo<Playwright, $this> */
    public function playwright(): BelongsTo
    {
        return $this->belongsTo(Playwright::class);
    }

    /** @return BelongsTo<Venue, $this> */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['season_sort' => 'integer', 'date_start' => 'date:Y-m-d', 'date_end' => 'date:Y-m-d'];
    }
}
