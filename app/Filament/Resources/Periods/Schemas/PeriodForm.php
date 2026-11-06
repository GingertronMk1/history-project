<?php

namespace App\Filament\Resources\Periods\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PeriodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required()->maxLength(255),
                Textarea::make('description')->columnSpanFull(),
            ]);
    }
}
