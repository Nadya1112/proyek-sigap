<?php

namespace App\Filament\Resources\DokumenRegulasiResource\Pages;

use App\Filament\Resources\DokumenRegulasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDokumenRegulasis extends ListRecords
{
    protected static string $resource = DokumenRegulasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Regulasi'),
        ];
    }
}