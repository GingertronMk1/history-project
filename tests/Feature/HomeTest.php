<?php

use App\Models\Company;
use App\Models\Period;
use App\Models\Playwright;
use App\Models\Season;
use App\Models\Show;
use App\Models\Venue;
use App\Models\Year;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->withoutVite();
});

test('renders the homepage with database counts and show metadata', function () {
    $year = Year::factory()->create(['name' => '25_26']);
    $playwright = Playwright::factory()->create(['name' => 'William Shakespeare']);
    Show::factory()->for($year)->for($playwright)->for(Company::factory())
        ->for(Venue::factory()->state(['name' => 'New Theatre']))->for(Season::factory())->for(Period::factory())
        ->create(['title' => 'Hamlet']);

    $this->get(route('home'))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('Welcome')
        ->where('counts', ['shows' => 1, 'years' => 1, 'playwrights' => 1, 'companies' => 1, 'venues' => 1, 'seasons' => 1, 'periods' => 1])
        ->has('featuredShows', 1)
        ->where('featuredShows.0.title', 'Hamlet')
        ->where('featuredShows.0.year', '25_26')
        ->where('featuredShows.0.playwright', 'William Shakespeare')
        ->where('featuredShows.0.venue', 'New Theatre'));
});

test('renders an empty homepage', function () {
    $this->get(route('home'))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('Welcome')->has('featuredShows', 0)
        ->where('counts', ['shows' => 0, 'years' => 0, 'playwrights' => 0, 'companies' => 0, 'venues' => 0, 'seasons' => 0, 'periods' => 0]));
});

test('excludes deleted records from homepage counts and selections', function () {
    $year = Year::factory()->create();
    $show = Show::factory()->for($year)->create();
    $show->delete();
    $year->delete();
    Company::factory()->create()->delete();
    Period::factory()->create()->delete();
    Playwright::factory()->create()->delete();
    Season::factory()->create()->delete();
    Venue::factory()->create()->delete();

    $this->get(route('home'))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->has('featuredShows', 0)
        ->where('counts', ['shows' => 0, 'years' => 0, 'playwrights' => 0, 'companies' => 0, 'venues' => 0, 'seasons' => 0, 'periods' => 0]));
});

test('limits the homepage show selection', function () {
    Show::factory()->count(13)->for(Year::factory())->create();

    $this->get(route('home'))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->has('featuredShows', 12)->where('counts.shows', 13));
});

test('handles missing and deleted parents in the show selection', function () {
    $year = Year::factory()->create();
    Show::factory()->for($year)->create();
    $year->delete();

    $this->get(route('home'))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('featuredShows.0.year', null)->where('featuredShows.0.playwright', null)->where('featuredShows.0.venue', null));
});
