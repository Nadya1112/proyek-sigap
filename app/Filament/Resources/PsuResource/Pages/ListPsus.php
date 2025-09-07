<?php

namespace App\Filament\Resources\PsuResource\Pages;

use App\Filament\Resources\PsuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPsus extends ListRecords
{
    protected static string $resource = PsuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
