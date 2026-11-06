<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\PlaywrightController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\ShowController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\YearController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::resource('companies', CompanyController::class)->only(['index', 'show']);
Route::resource('seasons', SeasonController::class)->only(['index', 'show']);
Route::resource('periods', PeriodController::class)->only(['index', 'show']);
Route::resource('playwrights', PlaywrightController::class)->only(['index', 'show']);
Route::resource('venues', VenueController::class)->only(['index', 'show']);
Route::resource('shows', ShowController::class)->only(['index', 'show']);

Route::resource('years', YearController::class)->only(['index', 'show']);
