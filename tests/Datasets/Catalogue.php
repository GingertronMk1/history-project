<?php

use App\Models\Company;
use App\Models\Period;
use App\Models\Playwright;
use App\Models\Season;
use App\Models\Show;
use App\Models\Venue;

dataset('catalogue entities', [
    'companies' => [Company::class, 'companies', 'company', 'name'],
    'seasons' => [Season::class, 'seasons', 'season', 'name'],
    'periods' => [Period::class, 'periods', 'period', 'name'],
    'playwrights' => [Playwright::class, 'playwrights', 'playwright', 'name'],
    'venues' => [Venue::class, 'venues', 'venue', 'name'],
    'shows' => [Show::class, 'shows', 'show', 'title'],
]);

dataset('supporting entities', [
    'companies' => [Company::class, 'companies', 'company'],
    'seasons' => [Season::class, 'seasons', 'season'],
    'periods' => [Period::class, 'periods', 'period'],
    'playwrights' => [Playwright::class, 'playwrights', 'playwright'],
    'venues' => [Venue::class, 'venues', 'venue'],
]);
