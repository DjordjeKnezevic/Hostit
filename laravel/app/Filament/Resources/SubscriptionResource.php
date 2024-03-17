<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Pricing;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Subscription;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SubscriptionResource\Pages;
use App\Filament\Resources\SubscriptionResource\RelationManagers;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('User')
                    ->options(User::query()->pluck('email', 'id'))
                    ->searchable(),
                Select::make('pricing_id')
                    ->label('Pricing Plan')
                    ->options(Pricing::query()->pluck('name', 'id'))
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        $pricing = Pricing::find($state);
                        $set('service_type', optional($pricing)->service_type);
                        $set('service_id', optional($pricing)->service_id);
                    }),
                TextInput::make('service_type')
                    ->label('Service Type')
                    ->disabled()
                    ->default(function (callable $get) {
                        $pricing = Pricing::find($get('pricing_id'));
                        return $pricing ? class_basename($pricing->service_type) : null;
                    }),
                TextInput::make('service_id')
                    ->label('Service')
                    ->disabled()
                    ->default(function (callable $get) {
                        $pricing = Pricing::find($get('pricing_id'));
                        return $pricing ? $pricing->service_id : null;
                    }),
                DateTimePicker::make('start_date')
                    ->label('Start Date'),
                DateTimePicker::make('end_date')
                    ->label('End Date')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service_type')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => class_basename($state)),
                Tables\Columns\TextColumn::make('service.name')
                    ->sortable()
                    ->searchable()
                    ->label('Service name'),
                Tables\Columns\TextColumn::make('pricing.name')
                    ->sortable()
                    ->searchable()
                    ->label('Pricing plan'),
                Tables\Columns\TextColumn::make('pricing.period')
                    ->sortable()
                    ->searchable()
                    ->label('Pricing period')
                    ->badge()
                    ->colors([
                        'warning' => 'monthly',
                        'success' => 'hourly',
                        'danger' => 'yearly',
                    ]),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                Filter::make('active')
                    ->label('Active Subscriptions')
                    ->query(fn (Builder $query) => $query
                        ->where('start_date', '<=', now())
                        ->where(function ($query) {
                            $query->where('end_date', '>=', now())
                                ->orWhereNull('end_date');
                        })),
                Filter::make('expired')
                    ->label('Expired Subscriptions')
                    ->query(fn (Builder $query) => $query
                        ->where('end_date', '<', now())),
                SelectFilter::make('service_type')
                    ->label('Service Type')
                    ->options([
                        'App\Models\Server' => 'Server',
                    ]),
                Filter::make('pricing_period')
                    ->form([
                        Select::make('period')
                            ->options(Pricing::query()->distinct()->pluck('period', 'period')->sort()->toArray())
                            ->placeholder('Select period')
                            ->label('Pricing Period'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['period'] ?? null,
                                fn (Builder $query, $period): Builder => $query->whereHas('pricing', fn (Builder $query) => $query->where('period', $period))
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!array_key_exists('period', $data) || !$data['period']) {
                            return null;
                        }

                        return 'Pricing Period: ' . $data['period'];
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}
