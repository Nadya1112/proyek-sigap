<?php

namespace App\Filament\Resources\DokumenRegulasiResource\Pages;

use App\Filament\Resources\DokumenRegulasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDokumenRegulasi extends EditRecord
{
    protected static string $resource = DokumenRegulasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
