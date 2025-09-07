<?php

namespace App\Filament\Admin\Resources\PsuResource\Pages;

use App\Filament\Admin\Resources\PsuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPsu extends EditRecord
{
    protected static string $resource = PsuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
