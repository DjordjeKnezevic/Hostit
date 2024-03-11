<?php

namespace App\Filament\Resources\ServerResource\Pages;

use Filament\Actions;
use App\Models\Server;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Validator;
use App\Filament\Resources\ServerResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateServer extends CreateRecord
{
    protected static string $resource = ServerResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $locationId = $data['location_id'];
        $serverTypeId = $data['server_type_id'];

        $exists = Server::where('location_id', $locationId)
            ->where('server_type_id', $serverTypeId)
            ->exists();

        if ($exists) {
            Notification::make()
                ->title("This Server already exists in this location")
                ->danger()
                ->send();
            $validator = Validator::make([], []);
            $validator->errors()->add('error', 'This Server already exists in this location');
            throw new ValidationException($validator);
        }

        return $data;
    }
}
