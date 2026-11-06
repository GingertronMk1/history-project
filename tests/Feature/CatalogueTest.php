<?php

use App\Models\Company;
use App\Models\Period;
use App\Models\Playwright;
use App\Models\Season;
use App\Models\Show;
use App\Models\Venue;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->withoutVite();
});

test('lists records alphabetically with pagination', function (string $model, string $plural, string $singular, string $field) {
    $model::factory()->count(25)->create([$field => 'Zulu']);
    $first = $model::factory()->create([$field => 'Alpha']);

    $this->get(route("{$plural}.index"))->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component("{$plural}/Index")
            ->has("{$plural}.data", 24)
            ->where("{$plural}.data.0.id", $first->id)
            ->where("{$plural}.total", 26)
            ->where("{$plural}.last_page", 2));

    $this->get(route("{$plural}.index", ['page' => 2]))->assertOk()
        ->assertInertia(fn (Assert $page) => $page->has("{$plural}.data", 2));
})->with('catalogue entities');

test('renders empty indexes', function (string $model, string $plural) {
    $this->get(route("{$plural}.index"))->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component("{$plural}/Index")->has("{$plural}.data", 0));
})->with('catalogue entities');

test('renders record details', function (string $model, string $plural, string $singular, string $field) {
    $record = $model::factory()->create([$field => 'Archive record', 'description' => 'Archive description']);

    $this->get(route("{$plural}.show", $record))->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component("{$plural}/Show")
            ->where("{$singular}.{$field}", 'Archive record')
            ->where("{$singular}.description", 'Archive description'));
})->with('catalogue entities');

test('returns 404 for missing records', function (string $model, string $plural) {
    $this->get(route("{$plural}.show", 999))->assertNotFound();
})->with('catalogue entities');

test('only lists shows belonging to the supporting record', function (string $model, string $plural, string $singular) {
    $record = $model::factory()->create();
    $show = Show::factory()->for($record, $singular)->create();
    Show::factory()->create();

    $this->get(route("{$plural}.show", $record))->assertOk()
        ->assertInertia(fn (Assert $page) => $page->has('shows.data', 1)->where('shows.data.0.id', $show->id));

    $this->get(route("{$plural}.index"))->assertOk()
        ->assertInertia(fn (Assert $page) => $page->where("{$plural}.data.0.shows_count", 1));
})->with('supporting entities');

test('shows all related records and performance dates', function () {
    $show = Show::factory()
        ->for(Company::factory()->state(['name' => 'New Theatre']))
        ->for(Season::factory()->state(['name' => 'Studio']))
        ->for(Period::factory()->state(['name' => 'Autumn']))
        ->for(Playwright::factory()->state(['name' => 'William Shakespeare']))
        ->for(Venue::factory()->state(['name' => 'Studio Theatre']))
        ->create(['date_start' => '2026-11-01', 'date_end' => '2026-11-03']);

    $this->get(route('shows.show', $show))->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('show.company.name', 'New Theatre')
            ->where('show.season.name', 'Studio')
            ->where('show.period.name', 'Autumn')
            ->where('show.playwright.name', 'William Shakespeare')
            ->where('show.venue.name', 'Studio Theatre')
            ->where('show.date_start', '2026-11-01')
            ->where('show.date_end', '2026-11-03'));
});

test('preserves shows when a supporting record is deleted', function (string $model, string $plural, string $singular) {
    $record = $model::factory()->create();
    $show = Show::factory()->for($record, $singular)->create();

    $record->delete();

    $this->assertModelExists($show);
    $this->assertDatabaseHas('shows', ['id' => $show->id, "{$singular}_id" => null]);
})->with('supporting entities');
