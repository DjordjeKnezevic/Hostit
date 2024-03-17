<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\RegionResource as Region;
use App\Models\Location;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RegionResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RegionResource\RelationManagers;

class RegionResource extends Resource
{
    protected static ?string $model = Region::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('location_id')
                    ->label('Location')
                    ->options(Location::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('total_cpu_cores')
                    ->label('Total CPU Cores')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('remaining_cpu_cores')
                    ->label('Remaining CPU Cores')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('total_ram')
                    ->label('Total RAM (in GB)')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('remaining_ram')
                    ->label('Remaining RAM (in GB)')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('total_storage')
                    ->label('Total Storage (in GB)')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('remaining_storage')
                    ->label('Remaining Storage (in GB)')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('total_bandwidth')
                    ->label('Total Bandwidth (in GB)')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('remaining_bandwidth')
                    ->label('Remaining Bandwidth (in GB)')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('location.name')
                    ->label('Location')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_cpu_cores')
                    ->label('Total CPU Cores')
                    ->sortable(),
                Tables\Columns\TextColumn::make('remaining_cpu_cores')
                    ->label('Remaining CPU Cores')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_ram')
                    ->label('Total RAM')
                    ->sortable(),
                Tables\Columns\TextColumn::make('remaining_ram')
                    ->label('Remaining RAM')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_storage')
                    ->label('Total Storage')
                    ->sortable(),
                Tables\Columns\TextColumn::make('remaining_storage')
                    ->label('Remaining Storage')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_bandwidth')
                    ->label('Total Bandwidth')
                    ->sortable(),
                Tables\Columns\TextColumn::make('remaining_bandwidth')
                    ->label('Remaining Bandwidth')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('location_id')
                    ->label('Location')
                    ->options(Location::all()->pluck('name', 'id'))
                    ->multiple(),
                Tables\Filters\Filter::make('low_cpu_cores')
                    ->label('Low CPU Cores')
                    ->query(fn ($query) => $query->whereRaw('remaining_cpu_cores < total_cpu_cores / 3')),

                Tables\Filters\Filter::make('low_ram')
                    ->label('Low RAM')
                    ->query(fn ($query) => $query->whereRaw('remaining_ram < total_ram / 3')),

                Tables\Filters\Filter::make('low_storage')
                    ->label('Low Storage')
                    ->query(fn ($query) => $query->whereRaw('remaining_storage < total_storage / 3')),

                Tables\Filters\Filter::make('low_bandwidth')
                    ->label('Low Bandwidth')
                    ->query(fn ($query) => $query->whereRaw('remaining_bandwidth < total_bandwidth / 3')),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListRegions::route('/'),
            'create' => Pages\CreateRegion::route('/create'),
            'edit' => Pages\EditRegion::route('/{record}/edit'),
        ];
    }
}
