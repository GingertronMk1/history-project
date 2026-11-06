<?php

use App\Filament\Resources\Seasons\Pages\CreateSeason;
use App\Filament\Resources\Seasons\Pages\EditSeason;
use App\Filament\Resources\Seasons\Pages\ListSeasons;
use App\Filament\Resources\Seasons\Pages\ViewSeason;
use App\Filament\Resources\Shows\Pages\CreateShow;
use App\Filament\Resources\Shows\Pages\EditShow;
use App\Filament\Resources\Shows\Pages\ListShows;
use App\Filament\Resources\Shows\Pages\ViewShow;
use App\Models\Company;
use App\Models\Period;
use App\Models\Playwright;
use App\Models\Season;
use App\Models\Show;
use App\Models\User;
use App\Models\Venue;
use App\Models\Year;
use Filament\Actions\DeleteAction;
use Livewire\Livewire;

beforeEach(function () {
    config(['app.env' => 'local']);
    $this->withoutVite();
});

test('requires login for admin resources', function (string $model, string $plural, string $singular) {
    $resource = 'App\\Filament\\Resources\\'.ucfirst($plural).'\\'.ucfirst($singular).'Resource';

    $this->get($resource::getUrl('index'))->assertRedirect(route('filament.admin.auth.login'));
})->with('catalogue entities');

test('renders admin list create view and edit pages', function (string $model, string $plural, string $singular) {
    $record = $model::factory()->create();
    $resource = 'App\\Filament\\Resources\\'.ucfirst($plural).'\\'.ucfirst($singular).'Resource';
    $this->actingAs(User::factory()->create());

    $this->get($resource::getUrl('index'))->assertOk();
    $this->get($resource::getUrl('create'))->assertOk();
    $this->get($resource::getUrl('view', ['record' => $record]))->assertOk();
    $this->get($resource::getUrl('edit', ['record' => $record]))->assertOk();
})->with('catalogue entities');

test('creates records through admin forms', function (string $model, string $plural, string $singular, string $field) {
    $this->actingAs(User::factory()->create());
    $page = 'App\\Filament\\Resources\\'.ucfirst($plural).'\\Pages\\Create'.ucfirst($singular);

    Livewire::test($page)->fillForm([$field => 'New archive record', 'description' => 'New description', ...($model === Show::class ? ['year_id' => Year::factory()->create()->id] : [])])
        ->call('create')->assertHasNoFormErrors()->assertRedirect();

    $this->assertDatabaseHas((new $model)->getTable(), [$field => 'New archive record', 'description' => 'New description']);
})->with('catalogue entities');

test('requires a name or title in admin forms', function (string $model, string $plural, string $singular, string $field) {
    $this->actingAs(User::factory()->create());
    $page = 'App\\Filament\\Resources\\'.ucfirst($plural).'\\Pages\\Create'.ucfirst($singular);

    Livewire::test($page)->fillForm([$field => null])->call('create')->assertHasFormErrors([$field => 'required']);

    $this->assertDatabaseCount((new $model)->getTable(), 0);
})->with('catalogue entities');

test('updates records through admin forms', function (string $model, string $plural, string $singular, string $field) {
    $record = $model::factory()->create();
    $this->actingAs(User::factory()->create());
    $page = 'App\\Filament\\Resources\\'.ucfirst($plural).'\\Pages\\Edit'.ucfirst($singular);

    Livewire::test($page, ['record' => $record->id])->fillForm([$field => 'Updated record'])
        ->call('save')->assertHasNoFormErrors();

    $this->assertDatabaseHas($record->getTable(), ['id' => $record->id, $field => 'Updated record']);
})->with('catalogue entities');

test('deletes records through admin actions', function (string $model, string $plural, string $singular) {
    $record = $model::factory()->create();
    $this->actingAs(User::factory()->create());
    $page = 'App\\Filament\\Resources\\'.ucfirst($plural).'\\Pages\\Edit'.ucfirst($singular);

    Livewire::test($page, ['record' => $record->id])->callAction(DeleteAction::class)->assertRedirect();

    $this->assertModelMissing($record);
})->with('catalogue entities');

test('rejects an end date before the start date', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateShow::class)->fillForm([
        'title' => 'Hamlet',
        'year_id' => Year::factory()->create()->id,
        'date_start' => '2026-11-06',
        'date_end' => '2026-11-05',
    ])->call('create')->assertHasFormErrors(['date_end' => 'after_or_equal']);

    $this->assertDatabaseCount('shows', 0);
});

test('saves show relationships and dates through the admin form', function () {
    $company = Company::factory()->create();
    $season = Season::factory()->create();
    $period = Period::factory()->create();
    $playwright = Playwright::factory()->create();
    $venue = Venue::factory()->create();
    $this->actingAs(User::factory()->create());
    $data = [
        'title' => 'Hamlet',
        'year_id' => Year::factory()->create()->id,
        'company_id' => $company->id,
        'season_id' => $season->id,
        'period_id' => $period->id,
        'playwright_id' => $playwright->id,
        'venue_id' => $venue->id,
        'date_start' => '2026-11-06',
        'date_end' => '2026-11-08',
    ];

    Livewire::test(CreateShow::class)->fillForm($data)->call('create')->assertHasNoFormErrors()->assertRedirect();

    $this->assertDatabaseHas('shows', $data);
});

test('rejects names and titles longer than 255 characters', function (string $model, string $plural, string $singular, string $field) {
    $this->actingAs(User::factory()->create());
    $page = 'App\\Filament\\Resources\\'.ucfirst($plural).'\\Pages\\Create'.ucfirst($singular);

    Livewire::test($page)->fillForm([$field => str_repeat('a', 256)])->call('create')->assertHasFormErrors([$field => 'max']);

    $this->assertDatabaseCount((new $model)->getTable(), 0);
})->with('catalogue entities');

test('rejects nonexistent show relationships', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateShow::class)->fillForm([
        'title' => 'Hamlet',
        'year_id' => Year::factory()->create()->id,
        'company_id' => 999,
        'season_id' => 999,
        'period_id' => 999,
        'playwright_id' => 999,
        'venue_id' => 999,
    ])->call('create')->assertHasFormErrors(['company_id', 'season_id', 'period_id', 'playwright_id', 'venue_id']);

    $this->assertDatabaseCount('shows', 0);
});

test('saves and displays show year and season order', function () {
    $year = Year::factory()->create(['name' => '24_25']);
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateShow::class)->fillForm([
        'title' => 'A Winter Show',
        'year_id' => $year->id,
        'season_sort' => 120,
    ])->call('create')->assertHasNoFormErrors()->assertRedirect();

    $show = Show::query()->sole();
    $this->assertDatabaseHas('shows', ['id' => $show->id, 'year_id' => $year->id, 'season_sort' => 120]);
    Livewire::test(ViewShow::class, ['record' => $show->id])
        ->assertSee('24_25')->assertSee('120');
    Livewire::test(ListShows::class)
        ->searchTable('24_25')->assertCanSeeTableRecords([$show])->assertSee('120');
});

test('updates show year and season order', function () {
    $show = Show::factory()->create(['season_sort' => 10]);
    $year = Year::factory()->create();
    $this->actingAs(User::factory()->create());

    Livewire::test(EditShow::class, ['record' => $show->id])
        ->fillForm(['year_id' => $year->id, 'season_sort' => 20])->call('save')->assertHasNoFormErrors();

    $this->assertDatabaseHas('shows', ['id' => $show->id, 'year_id' => $year->id, 'season_sort' => 20]);
});

test('validates the required show year', function (mixed $year, string $rule) {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateShow::class)->fillForm(['title' => 'Hamlet', 'year_id' => $year])
        ->call('create')->assertHasFormErrors(['year_id' => $rule]);

    $this->assertDatabaseCount('shows', 0);
})->with([
    'missing year' => [null, 'required'],
    'nonexistent year' => [999, 'in'],
]);

test('rejects invalid season sort values', function (mixed $sort, string $rule) {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateShow::class)->fillForm(['title' => 'Hamlet', 'year_id' => Year::factory()->create()->id, 'season_sort' => $sort])
        ->call('create')->assertHasFormErrors(['season_sort' => $rule]);

    $this->assertDatabaseCount('shows', 0);
})->with([
    'negative' => [-1, 'min'],
    'fractional' => [1.5, 'integer'],
    'too large' => [4294967296, 'max'],
    'missing' => [null, 'required'],
]);

test('defaults season colour to white', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateSeason::class)
        ->fillForm(['name' => 'Studio'])->call('create')->assertHasNoFormErrors()->assertRedirect();

    $this->assertDatabaseHas('seasons', ['name' => 'Studio', 'colour' => '#ffffff']);
});

test('saves and displays season colour', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateSeason::class)
        ->fillForm(['name' => 'Studio', 'colour' => '#123abc'])->call('create')->assertHasNoFormErrors()->assertRedirect();

    $season = Season::query()->sole();
    $this->assertDatabaseHas('seasons', ['id' => $season->id, 'colour' => '#123abc']);
    Livewire::test(ViewSeason::class, ['record' => $season->id])
        ->assertSee('#123abc');
    Livewire::test(ListSeasons::class)->assertSee('#123abc');
});

test('updates season colour', function () {
    $season = Season::factory()->create(['colour' => '#ffffff']);
    $this->actingAs(User::factory()->create());

    Livewire::test(EditSeason::class, ['record' => $season->id])
        ->fillForm(['colour' => '#654321'])->call('save')->assertHasNoFormErrors();

    $this->assertDatabaseHas('seasons', ['id' => $season->id, 'colour' => '#654321']);
});

test('rejects missing and invalid season colours', function (?string $colour, string $rule) {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateSeason::class)
        ->fillForm(['name' => 'Studio', 'colour' => $colour])->call('create')->assertHasFormErrors(['colour' => $rule]);

    $this->assertDatabaseCount('seasons', 0);
})->with([
    'missing colour' => [null, 'required'],
    'invalid colour' => ['not-a-colour', 'hex_color'],
]);
