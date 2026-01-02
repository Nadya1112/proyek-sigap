<?php

namespace App\Filament\Resources\KomplekResource\Pages;

use App\Filament\Resources\KomplekResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKompleks extends ListRecords
{
    protected static string $resource = KomplekResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Komplek Perumahan'),
        ];
    }
}
