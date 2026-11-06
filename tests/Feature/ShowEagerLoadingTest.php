<?php

use App\Models\Company;
use App\Models\Period;
use App\Models\Playwright;
use App\Models\Season;
use App\Models\Show;
use App\Models\Venue;
use App\Models\Year;

test('eager loads all belongs-to relationships with a fixed number of queries', function () {
    $company = Company::factory()->create();
    $season = Season::factory()->create();
    $period = Period::factory()->create();
    $playwright = Playwright::factory()->create();
    $venue = Venue::factory()->create();
    $year = Year::factory()->create();
    Show::factory()->count(3)
        ->for($company)->for($season)->for($period)->for($playwright)->for($venue)->for($year)
        ->create();
    $this->expectsDatabaseQueryCount(7);

    $shows = Show::query()->get();

    expect($shows)->toHaveCount(3);
    foreach ($shows as $show) {
        expect($show->relationLoaded('company'))->toBeTrue();
        expect($show->relationLoaded('season'))->toBeTrue();
        expect($show->relationLoaded('period'))->toBeTrue();
        expect($show->relationLoaded('playwright'))->toBeTrue();
        expect($show->relationLoaded('venue'))->toBeTrue();
        expect($show->relationLoaded('year'))->toBeTrue();
        expect($show->company->is($company))->toBeTrue();
        expect($show->season->is($season))->toBeTrue();
        expect($show->period->is($period))->toBeTrue();
        expect($show->playwright->is($playwright))->toBeTrue();
        expect($show->venue->is($venue))->toBeTrue();
        expect($show->year->is($year))->toBeTrue();
    }
});

test('eager loads missing optional parents as null', function () {
    $record = Show::factory()->create();
    $this->expectsDatabaseQueryCount(2);

    $show = Show::query()->findOrFail($record->id);

    foreach (['company', 'season', 'period', 'playwright', 'venue'] as $relationship) {
        expect($show->relationLoaded($relationship))->toBeTrue();
        expect($show->getRelation($relationship))->toBeNull();
    }
    expect($show->relationLoaded('year'))->toBeTrue();
    expect($show->year->id)->toBe($record->year_id);
});
