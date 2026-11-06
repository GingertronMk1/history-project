<?php

use App\Filament\Resources\Shows\Pages\CreateShow;
use App\Filament\Resources\Shows\Pages\ListShows;
use App\Filament\Resources\Years\Pages\CreateYear;
use App\Filament\Resources\Years\Pages\EditYear;
use App\Filament\Resources\Years\Pages\ListYears;
use App\Models\Show;
use App\Models\User;
use App\Models\Year;
use Filament\Forms\Components\Select;
use Livewire\Livewire;

beforeEach(function () {
    config(['app.env' => 'local']);
    $this->withoutVite();
});

test('orders year queries by start year across the century boundary', function () {
    $latest = Year::factory()->create(['name' => '25_26', 'year_start' => 2025]);
    $oldest = Year::factory()->create(['name' => '99_00', 'year_start' => 1999]);
    $middle = Year::factory()->create(['name' => '00_01', 'year_start' => 2000]);

    expect(Year::query()->pluck('id')->all())->toBe([$oldest->id, $middle->id, $latest->id]);
});

test('uses start year for default and name-column sorting in Filament', function () {
    $latest = Year::factory()->create(['name' => '25_26', 'year_start' => 2025]);
    $oldest = Year::factory()->create(['name' => '99_00', 'year_start' => 1999]);
    $middle = Year::factory()->create(['name' => '00_01', 'year_start' => 2000]);
    $this->actingAs(User::factory()->create());

    Livewire::test(ListYears::class)
        ->assertCanSeeTableRecords([$oldest, $middle, $latest], inOrder: true)
        ->sortTable('name', 'desc')
        ->assertCanSeeTableRecords([$latest, $middle, $oldest], inOrder: true);
});

test('orders the show year selector by start year', function () {
    $latest = Year::factory()->create(['name' => '25_26', 'year_start' => 2025]);
    $oldest = Year::factory()->create(['name' => '99_00', 'year_start' => 1999]);
    $middle = Year::factory()->create(['name' => '00_01', 'year_start' => 2000]);
    Year::factory()->create(['name' => '90_91', 'year_start' => 1990])->delete();
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateShow::class)->assertFormFieldExists('year_id', fn (Select $field): bool => array_keys($field->getSearchResults('')) === [$oldest->id, $middle->id, $latest->id]);
});

test('reorders years after editing their start year', function () {
    $first = Year::factory()->create(['name' => '99_00', 'year_start' => 1999]);
    $changed = Year::factory()->create(['name' => '00_01', 'year_start' => 2000]);
    $this->actingAs(User::factory()->create());

    Livewire::test(EditYear::class, ['record' => $changed->id])
        ->fillForm(['year_start' => 1990])->call('save')->assertHasNoFormErrors();

    $this->assertDatabaseHas('years', ['id' => $changed->id, 'year_start' => 1990]);
    expect(Year::query()->pluck('id')->all())->toBe([$changed->id, $first->id]);
});

test('rejects invalid start years', function (mixed $startYear, string $rule) {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateYear::class)->fillForm(['name' => '25_26', 'year_start' => $startYear])
        ->call('create')->assertHasFormErrors(['year_start' => $rule]);

    $this->assertDatabaseCount('years', 0);
})->with([
    'missing start year' => [null, 'required'],
    'negative start year' => [-1, 'min'],
    'fractional start year' => [2025.5, 'integer'],
    'start year too large' => [4294967296, 'max'],
]);

test('sorts shows by their year start rather than year name', function () {
    $latest = Show::factory()->for(Year::factory()->state(['name' => '25_26', 'year_start' => 2025]))->create();
    $oldest = Show::factory()->for(Year::factory()->state(['name' => '99_00', 'year_start' => 1999]))->create();
    $middle = Show::factory()->for(Year::factory()->state(['name' => '00_01', 'year_start' => 2000]))->create();
    $this->actingAs(User::factory()->create());

    Livewire::test(ListShows::class)->sortTable('year.name', 'asc')
        ->assertCanSeeTableRecords([$oldest, $middle, $latest], inOrder: true);
});
