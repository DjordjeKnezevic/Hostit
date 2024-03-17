<?php

namespace App\Filament\Resources\SubscriptionResource\Pages;

use Carbon\Carbon;
use Filament\Actions;
use App\Models\Pricing;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Validator;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;
use App\Filament\Resources\SubscriptionResource;

class CreateSubscription extends CreateRecord
{
    protected static string $resource = SubscriptionResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        $startDate = $data['start_date'] ?? now();
        $endDate = $data['end_date'] ?? null;

        $startDate = Carbon::parse($startDate);
        $endDate = $endDate ? Carbon::parse($endDate) : null;

        $pricing = Pricing::find($data['pricing_id']);

        if ($pricing) {
            $data['service_type'] = $pricing->service_type;
            $data['service_id'] = $pricing->service_id;
        }

        if ($endDate && $startDate->gt($endDate)) {
            Notification::make()
                ->title("The start date must be before the end date")
                ->danger()
                ->send();
            $validator = Validator::make([], []);
            $validator->errors()->add('error', 'The start date must be before the end date');
            throw new ValidationException($validator);
        }

        $data['start_date'] = $startDate;
        return $data;
    }
}
