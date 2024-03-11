<?php

namespace App\Filament\Resources\ServerTypeResource\Pages;

use App\Filament\Resources\ServerTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServerType extends EditRecord
{
    protected static string $resource = ServerTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
