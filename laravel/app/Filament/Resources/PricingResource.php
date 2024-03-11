<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Pricing;
use App\Models\Server;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PricingResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PricingResource\RelationManagers;

class PricingResource extends Resource
{
    protected static ?string $model = Pricing::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Name'),
                Forms\Components\Select::make('service_type')
                    ->required()
                    ->options([
                        'App\Models\Server' => 'Server',
                    ])
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('service_id', null))
                    ->label('Service Type'),
                Forms\Components\Select::make('service_id')
                    ->label('Service')
                    ->options(function (callable $get) {
                        $serviceType = $get('service_type');
                        if ($serviceType) {
                            return $serviceType::query()->pluck('name', 'id')->toArray();
                        }
                        return [];
                    })
                    ->reactive()
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->label('Price'),
                Forms\Components\Select::make('period')
                    ->required()
                    ->options([
                        'hourly' => 'Hourly',
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                    ])
                    ->label('Period'),
                DatePicker::make('valid_from')
                    ->label('Valid From')
                    ->rules(['nullable', 'date']),
                DatePicker::make('valid_until')
                    ->label('Valid Until')
                    ->rules(['nullable', 'date']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('service_type')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => class_basename($state)),
                Tables\Columns\TextColumn::make('service.name')->sortable()->searchable()->label('Service name'),
                Tables\Columns\TextColumn::make('price')->sortable()->searchable(),
                Tables\Columns\ViewColumn::make('period')->sortable()->searchable()->view('components.period-cell'),
                Tables\Columns\TextColumn::make('valid_from')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('valid_until')->dateTime()->sortable(),

            ])
            ->filters([
                SelectFilter::make('period')
                    ->options([
                        'hourly' => 'Hourly',
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                    ]),

                Filter::make('active')
                    ->label('Active Prices')
                    ->query(
                        fn (Builder $query): Builder => $query
                            ->where('valid_from', '<=', now())
                            ->where(function ($query) {
                                $query->where('valid_until', '>=', now())
                                    ->orWhereNull('valid_until');
                            })
                    ),

                Filter::make('expired')
                    ->label('Expired Prices')
                    ->query(
                        fn (Builder $query): Builder => $query
                            ->where('valid_until', '<', now())
                    ),

                Filter::make('not_yet_active')
                    ->label('Not Yet Active')
                    ->query(
                        fn (Builder $query): Builder => $query
                            ->where('valid_from', '>', now())
                    ),
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
            'index' => Pages\ListPricings::route('/'),
            'create' => Pages\CreatePricing::route('/create'),
            'edit' => Pages\EditPricing::route('/{record}/edit'),
        ];
    }
}
