<?php

use App\Filament\Resources\Years\Pages\CreateYear;
use App\Filament\Resources\Years\Pages\EditYear;
use App\Filament\Resources\Years\Pages\ListYears;
use App\Filament\Resources\Years\Pages\ViewYear;
use App\Filament\Resources\Years\YearResource;
use App\Models\User;
use App\Models\Year;
use Filament\Actions\DeleteAction;
use Livewire\Livewire;

beforeEach(function () {
    config(['app.env' => 'local']);
    $this->withoutVite();
});

test('requires login for year administration', function () {
    $this->get(YearResource::getUrl('index'))->assertRedirect(route('filament.admin.auth.login'));
});

test('renders year administration pages', function () {
    $year = Year::factory()->create();
    $this->actingAs(User::factory()->create());

    $this->get(YearResource::getUrl('index'))->assertOk();
    $this->get(YearResource::getUrl('create'))->assertOk();
    $this->get(YearResource::getUrl('view', ['record' => $year]))->assertOk();
    $this->get(YearResource::getUrl('edit', ['record' => $year]))->assertOk();
});

test('creates a year with an optional description', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateYear::class)->fillForm(['name' => '25_26', 'description' => null])
        ->call('create')->assertHasNoFormErrors()->assertRedirect();

    $this->assertDatabaseHas('years', ['name' => '25_26', 'description' => null]);
});

test('updates a year while keeping its name', function () {
    $year = Year::factory()->create(['name' => '25_26', 'description' => 'Original']);
    $this->actingAs(User::factory()->create());

    Livewire::test(EditYear::class, ['record' => $year->id])
        ->fillForm(['name' => '25_26', 'description' => 'Updated description'])
        ->call('save')->assertHasNoFormErrors();

    $this->assertDatabaseHas('years', ['id' => $year->id, 'name' => '25_26', 'description' => 'Updated description']);
});

test('renames a year', function () {
    $year = Year::factory()->create(['name' => '24_25']);
    $this->actingAs(User::factory()->create());

    Livewire::test(EditYear::class, ['record' => $year->id])
        ->fillForm(['name' => '25_26'])->call('save')->assertHasNoFormErrors();

    $this->assertDatabaseHas('years', ['id' => $year->id, 'name' => '25_26']);
});

test('displays year names and descriptions', function () {
    $year = Year::factory()->create(['name' => '25_26', 'description' => 'A year of theatre']);
    $this->actingAs(User::factory()->create());

    Livewire::test(ViewYear::class, ['record' => $year->id])->assertSee('25_26')->assertSee('A year of theatre');
});

test('searches years by name', function () {
    $year = Year::factory()->create(['name' => '25_26']);
    $otherYear = Year::factory()->create(['name' => '24_25']);
    $this->actingAs(User::factory()->create());

    Livewire::test(ListYears::class)->searchTable('25_26')
        ->assertCanSeeTableRecords([$year])->assertCanNotSeeTableRecords([$otherYear]);
});

test('rejects invalid year names', function (?string $name, string $rule) {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateYear::class)->fillForm(['name' => $name])->call('create')->assertHasFormErrors(['name' => $rule]);

    $this->assertDatabaseCount('years', 0);
})->with([
    'missing name' => [null, 'required'],
    'name too long' => [str_repeat('a', 256), 'max'],
]);

test('rejects duplicate year names on creation', function () {
    Year::factory()->create(['name' => '25_26']);
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateYear::class)->fillForm(['name' => '25_26'])->call('create')->assertHasFormErrors(['name' => 'unique']);

    $this->assertDatabaseCount('years', 1);
});

test('rejects duplicate year names on editing', function () {
    Year::factory()->create(['name' => '25_26']);
    $year = Year::factory()->create(['name' => '24_25']);
    $this->actingAs(User::factory()->create());

    Livewire::test(EditYear::class, ['record' => $year->id])->fillForm(['name' => '25_26'])
        ->call('save')->assertHasFormErrors(['name' => 'unique']);

    $this->assertDatabaseHas('years', ['id' => $year->id, 'name' => '24_25']);
});

test('deletes a year', function () {
    $year = Year::factory()->create();
    $this->actingAs(User::factory()->create());

    Livewire::test(EditYear::class, ['record' => $year->id])->callAction(DeleteAction::class)->assertRedirect();

    $this->assertModelMissing($year);
});
