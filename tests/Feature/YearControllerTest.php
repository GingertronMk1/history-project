<?php

use App\Models\Show;
use App\Models\Year;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->withoutVite();
});

test('lists years with show counts and pagination', function () {
    $year = Year::factory()->create(['name' => '00_01']);
    Show::factory()->for($year)->create();
    Year::factory()->count(24)->sequence(fn (Sequence $sequence): array => ['name' => 'Year '.$sequence->index])->create();

    $this->get(route('years.index'))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('years/Index')->has('years.data', 24)
        ->where('years.total', 25)->where('years.last_page', 2)
        ->where('years.data.0.id', $year->id)->where('years.data.0.shows_count', 1));
    $this->get(route('years.index', ['page' => 2]))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->has('years.data', 1)->where('years.current_page', 2));
});

test('renders an empty years index', function () {
    $this->get(route('years.index'))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('years/Index')->has('years.data', 0));
});

test('shows a year and only its own shows', function () {
    $year = Year::factory()->create(['name' => '25_26', 'description' => 'The theatre year']);
    $show = Show::factory()->for($year)->create(['title' => 'Hamlet']);
    Show::factory()->for(Year::factory()->state(['name' => '24_25']))->create();

    $this->get(route('years.show', $year))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('years/Show')->where('year.name', '25_26')->where('year.description', 'The theatre year')
        ->has('shows.data', 1)->where('shows.data.0.id', $show->id));
});

test('paginates a year shows alphabetically', function () {
    $year = Year::factory()->create();
    Show::factory()->for($year)->count(24)->create(['title' => 'Zulu']);
    $firstShow = Show::factory()->for($year)->create(['title' => 'Alpha']);

    $this->get(route('years.show', $year))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->has('shows.data', 24)->where('shows.total', 25)->where('shows.data.0.id', $firstShow->id));
    $this->get(route('years.show', [$year, 'page' => 2]))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->has('shows.data', 1)->where('shows.current_page', 2));
});

test('renders a year without shows', function () {
    $year = Year::factory()->create(['description' => null]);

    $this->get(route('years.show', $year))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('years/Show')->where('year.description', null)->has('shows.data', 0));
});

test('returns 404 for a missing year', function () {
    $this->get(route('years.show', 999))->assertNotFound();
});

test('includes the linked year on show details', function () {
    $year = Year::factory()->create(['name' => '25_26']);
    $show = Show::factory()->for($year)->create();

    $this->get(route('shows.show', $show))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('show.year.id', $year->id)->where('show.year.name', '25_26'));
});
