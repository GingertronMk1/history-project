<?php

namespace App\Filament\Resources\Shows\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ShowInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('year.name')->label('Year'),
                TextEntry::make('season_sort'),
                TextEntry::make('description')->placeholder('No description yet.')->columnSpanFull(),
                TextEntry::make('company.name')->label('Company')->placeholder('Not recorded'),
                TextEntry::make('season.name')->label('Season')->placeholder('Not recorded'),
                TextEntry::make('period.name')->label('Period')->placeholder('Not recorded'),
                TextEntry::make('playwright.name')->label('Playwright')->placeholder('Not recorded'),
                TextEntry::make('venue.name')->label('Venue')->placeholder('Not recorded'),
                TextEntry::make('date_start')->date()->placeholder('Not recorded'),
                TextEntry::make('date_end')->date()->placeholder('Not recorded'),
            ]);
    }
}
