<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Period;
use App\Models\Playwright;
use App\Models\Season;
use App\Models\Show;
use App\Models\Venue;
use App\Models\Year;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Welcome', [
            'counts' => [
                'shows' => Show::query()->count(),
                'years' => Year::query()->count(),
                'playwrights' => Playwright::query()->count(),
                'companies' => Company::query()->count(),
                'venues' => Venue::query()->count(),
                'seasons' => Season::query()->count(),
                'periods' => Period::query()->count(),
            ],
            'featuredShows' => Show::query()
                ->select(['id', 'title', 'year_id', 'company_id', 'season_id', 'period_id', 'playwright_id', 'venue_id'])
                ->inRandomOrder()
                ->limit(12)
                ->get()
                ->map(fn (Show $show): array => [
                    'id' => $show->id,
                    'title' => $show->title,
                    'year' => $show->year?->name,
                    'playwright' => $show->playwright?->name,
                    'venue' => $show->venue?->name,
                ]),
        ]);
    }
}
