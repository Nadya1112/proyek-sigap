<?php

namespace App\Filament\Admin\Resources\KomplekResource\Pages;

use App\Filament\Admin\Resources\KomplekResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKomplek extends EditRecord
{
    protected static string $resource = KomplekResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
