<?php

namespace App\Filament\Resources\Playwrights\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PlaywrightInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('description')->placeholder('No description yet.')->columnSpanFull(),
            ]);
    }
}
