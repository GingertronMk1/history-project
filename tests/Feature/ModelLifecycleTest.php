<?php

use App\Models\Company;
use App\Models\Period;
use App\Models\Playwright;
use App\Models\Season;
use App\Models\Show;
use App\Models\User;
use App\Models\Venue;
use App\Models\Year;
use Filament\Actions\RestoreAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use Livewire\Livewire;

beforeEach(function () {
    config(['app.env' => 'local']);
    $this->withoutVite();
});

dataset('application models', [
    'company' => [Company::class],
    'period' => [Period::class],
    'playwright' => [Playwright::class],
    'season' => [Season::class],
    'show' => [Show::class],
    'user' => [User::class],
    'venue' => [Venue::class],
    'year' => [Year::class],
]);

dataset('soft deleting resources', [
    'company' => [Company::class, 'Companies', 'Company'],
    'period' => [Period::class, 'Periods', 'Period'],
    'playwright' => [Playwright::class, 'Playwrights', 'Playwright'],
    'season' => [Season::class, 'Seasons', 'Season'],
    'show' => [Show::class, 'Shows', 'Show'],
    'venue' => [Venue::class, 'Venues', 'Venue'],
    'year' => [Year::class, 'Years', 'Year'],
]);

test('generates unique UUIDv7 primary keys', function (string $model) {
    $first = $model::factory()->create();
    $second = $model::factory()->create();

    expect(Str::isUuid($first->id, 7))->toBeTrue();
    expect(Str::isUuid($second->id, 7))->toBeTrue();
    expect($first->id)->not->toBe($second->id);
    expect($model::query()->findOrFail($first->id)->id)->toBe($first->id);
})->with('application models');

test('soft deletes and restores application records', function (string $model) {
    $record = $model::factory()->create();

    $record->delete();

    $this->assertSoftDeleted($record);
    expect($model::query()->find($record->id))->toBeNull();
    expect($model::withTrashed()->findOrFail($record->id)->id)->toBe($record->id);

    $record->restore();

    $this->assertNotSoftDeleted($record);
    expect($model::query()->findOrFail($record->id)->id)->toBe($record->id);
})->with('application models');

test('hides deleted records from public indexes and details', function (string $model, string $plural) {
    $record = $model::factory()->create();
    $record->delete();
    $route = strtolower($plural);

    $this->get(route("{$route}.index"))->assertOk()->assertInertia(fn (Assert $page) => $page->has("{$route}.data", 0));
    $this->get(route("{$route}.show", $record))->assertNotFound();
})->with('soft deleting resources');

test('restores deleted records through Filament', function (string $model, string $plural, string $singular) {
    $record = $model::factory()->create();
    $record->delete();
    $this->actingAs(User::factory()->create());
    $page = "App\\Filament\\Resources\\{$plural}\\Pages\\Edit{$singular}";

    Livewire::test($page, ['record' => $record->id])->callAction(RestoreAction::class)->assertNotified();

    $this->assertNotSoftDeleted($record);
    expect($model::query()->findOrFail($record->id)->id)->toBe($record->id);
})->with('soft deleting resources');

test('filters deleted records in Filament tables', function (string $model, string $plural) {
    $active = $model::factory()->create();
    $deleted = $model::factory()->create();
    $deleted->delete();
    $this->actingAs(User::factory()->create());
    $page = "App\\Filament\\Resources\\{$plural}\\Pages\\List{$plural}";

    Livewire::test($page)->assertCanSeeTableRecords([$active])->assertCanNotSeeTableRecords([$deleted])
        ->filterTable('trashed', false)->assertCanSeeTableRecords([$deleted])->assertCanNotSeeTableRecords([$active]);
})->with('soft deleting resources');

test('preserves UUID parent links when a parent is deleted and restored', function () {
    $company = Company::factory()->create();
    $show = Show::factory()->for($company)->create();

    $company->delete();

    $this->assertDatabaseHas('shows', ['id' => $show->id, 'company_id' => $company->id]);
    expect($show->fresh()->company)->toBeNull();

    $company->restore();

    expect($show->fresh()->company->id)->toBe($company->id);
});

test('authenticates active users with UUIDs', function () {
    $user = User::factory()->create(['email' => 'active@example.com']);

    expect(Auth::attempt(['email' => 'active@example.com', 'password' => 'password']))->toBeTrue();
    expect(Auth::id())->toBe($user->id);
});

test('rejects authentication for a deleted user', function () {
    $user = User::factory()->create(['email' => 'deleted@example.com']);
    $user->delete();

    expect(Auth::attempt(['email' => 'deleted@example.com', 'password' => 'password']))->toBeFalse();
});
