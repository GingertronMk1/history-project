<?php

namespace App\Filament\Resources\Years\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class YearInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('year_start')->label('Start year'),
                TextEntry::make('description')->placeholder('No description yet.')->columnSpanFull(),
            ]);
    }
}
