<?php

namespace App\Filament\Resources\KomplekBaruResource\Pages;

use App\Filament\Resources\KomplekBaruResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKomplekBaru extends EditRecord
{
    protected static string $resource = KomplekBaruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
