<?php

namespace App\Filament\Resources\ServerTypeResource\Pages;

use App\Filament\Resources\ServerTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServerTypes extends ListRecords
{
    protected static string $resource = ServerTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
