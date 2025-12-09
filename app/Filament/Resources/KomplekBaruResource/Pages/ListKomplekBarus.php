<?php

namespace App\Filament\Resources\KomplekBaruResource\Pages;

use App\Filament\Resources\KomplekBaruResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKomplekBarus extends ListRecords
{
    protected static string $resource = KomplekBaruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
