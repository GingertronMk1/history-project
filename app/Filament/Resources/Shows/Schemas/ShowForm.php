<?php

namespace App\Filament\Resources\Shows\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ShowForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->required()->maxLength(255),
                TextInput::make('year')->required()->maxLength(255)->helperText('Academic year, for example 25_26.'),
                TextInput::make('season_sort')->integer()->minValue(0)->maxValue(4294967295)->required()->default(0),
                Textarea::make('description')->columnSpanFull(),
                Select::make('company_id')->relationship('company', 'name')->searchable()->exists('companies', 'id'),
                Select::make('season_id')->relationship('season', 'name')->searchable()->exists('seasons', 'id'),
                Select::make('period_id')->relationship('period', 'name')->searchable()->exists('periods', 'id'),
                Select::make('playwright_id')->relationship('playwright', 'name')->searchable()->exists('playwrights', 'id'),
                Select::make('venue_id')->relationship('venue', 'name')->searchable()->exists('venues', 'id'),
                DatePicker::make('date_start'),
                DatePicker::make('date_end')->afterOrEqual('date_start'),
            ]);
    }
}
