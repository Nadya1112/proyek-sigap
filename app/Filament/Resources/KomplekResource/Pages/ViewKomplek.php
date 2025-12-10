<?php

namespace App\Filament\Resources\KomplekResource\Pages;

use App\Filament\Resources\KomplekResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKomplek extends ViewRecord
{
    protected static string $resource = KomplekResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
