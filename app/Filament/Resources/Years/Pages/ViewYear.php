<?php

namespace App\Filament\Resources\Years\Pages;

use App\Filament\Resources\Years\YearResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewYear extends ViewRecord
{
    protected static string $resource = YearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
