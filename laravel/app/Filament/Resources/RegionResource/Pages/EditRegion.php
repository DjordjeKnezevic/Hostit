<?php

namespace App\Filament\Resources\RegionResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Validator;
use App\Filament\Resources\RegionResource;
use Illuminate\Validation\ValidationException;

class EditRegion extends EditRecord
{
    protected static string $resource = RegionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        $resourcePairs = [
            ['total_cpu_cores', 'remaining_cpu_cores', 'CPU Cores'],
            ['total_ram', 'remaining_ram', 'RAM'],
            ['total_storage', 'remaining_storage', 'Storage'],
            ['total_bandwidth', 'remaining_bandwidth', 'Bandwidth'],
        ];

        foreach ($resourcePairs as $resource) {
            [$totalField, $remainingField, $fieldName] = $resource;

            if (isset($data[$totalField]) && $data[$totalField] < 0) {
                $this->notifyValidationError("$fieldName total cannot be less than 0", $totalField);
            }
            if (isset($data[$remainingField]) && $data[$remainingField] < 0) {
                $this->notifyValidationError("$fieldName remaining cannot be less than 0", $remainingField);
            }

            if (isset($data[$totalField], $data[$remainingField]) && $data[$remainingField] > $data[$totalField]) {
                $this->notifyValidationError("Remaining $fieldName cannot be larger than total", $remainingField);
            }
        }

        return $data;
    }

    protected function notifyValidationError($message, $field)
    {
        Notification::make()
            ->title($message)
            ->danger()
            ->send();

        $validator = Validator::make([], []);
        $validator->errors()->add($field, $message);
        throw new ValidationException($validator);
    }
}
