<?php

namespace App\Filament\Resources\Years\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class YearForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required()->maxLength(255)->unique(ignoreRecord: true)->helperText('Academic year, for example 25_26.'),
                TextInput::make('year_start')->label('Start year')->integer()->minValue(0)->maxValue(4294967295)->required(),
                Textarea::make('description')->columnSpanFull(),
            ]);
    }
}
