<?php

namespace App\Filament\Resources\PricingResource\Pages;

use Carbon\Carbon;
use Filament\Actions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Validator;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PricingResource;
use Illuminate\Validation\ValidationException;

class CreatePricing extends CreateRecord
{
    protected static string $resource = PricingResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $validFrom = $data['valid_from'] ?? now();
        $validUntil = $data['valid_until'] ?? null;

        $validFrom = $validFrom ? Carbon::parse($validFrom) : now();
        $validUntil = $validUntil ? Carbon::parse($validUntil) : null;

        if ($validUntil && $validFrom->gt($validUntil)) {
            Notification::make()
                ->title("The valid from date must be before the valid until date")
                ->danger()
                ->send();
            $validator = Validator::make([], []);
            $validator->errors()->add('error', 'The valid from date must be before the valid until date');
            throw new ValidationException($validator);
        }

        $data['valid_from'] = $validFrom ?? now();
        return $data;
    }
}
