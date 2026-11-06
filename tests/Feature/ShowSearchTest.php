<?php

use App\Models\Show;
use App\Models\Year;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->withoutVite();
});

test('finds shows by title without matching case', function () {
    $show = Show::factory()->create(['title' => 'Hamlet']);
    Show::factory()->create(['title' => 'Macbeth']);

    $this->get(route('shows.index', ['q' => ' hAMl ']))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('search', 'hAMl')->has('shows.data', 1)->where('shows.data.0.id', $show->id));
});

test('returns all shows for an empty search', function () {
    Show::factory()->count(2)->create();

    $this->get(route('shows.index', ['q' => ' ']))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('search', '')->has('shows.data', 2));
});

test('returns no results when a title does not match', function () {
    Show::factory()->create(['title' => 'Hamlet']);

    $this->get(route('shows.index', ['q' => 'Macbeth']))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('search', 'Macbeth')->has('shows.data', 0));
});

test('excludes deleted shows from search results', function () {
    Show::factory()->create(['title' => 'Hamlet'])->delete();

    $this->get(route('shows.index', ['q' => 'Hamlet']))->assertOk()->assertInertia(fn (Assert $page) => $page->has('shows.data', 0));
});

test('preserves search terms in pagination links', function () {
    Show::factory()->count(25)->for(Year::factory())->create(['title' => 'Hamlet']);
    Show::factory()->create(['title' => 'Macbeth']);

    $this->get(route('shows.index', ['q' => 'Hamlet']))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('shows.total', 25)->has('shows.data', 24)
        ->where('shows.next_page_url', route('shows.index', ['q' => 'Hamlet', 'page' => 2])));
    $this->get(route('shows.index', ['q' => 'Hamlet', 'page' => 2]))->assertOk()->assertInertia(fn (Assert $page) => $page
        ->where('search', 'Hamlet')->has('shows.data', 1));
});

test('rejects invalid search inputs', function (mixed $query) {
    $this->get(route('shows.index', ['q' => $query]))->assertSessionHasErrors('q');
})->with([
    'array input' => [['Hamlet']],
    'query too long' => [str_repeat('a', 201)],
]);
