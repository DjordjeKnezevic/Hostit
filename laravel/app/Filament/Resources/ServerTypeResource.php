<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\ServerType;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ServerTypeResource\Pages;
use App\Filament\Resources\ServerTypeResource\RelationManagers;

class ServerTypeResource extends Resource
{
    protected static ?string $model = ServerType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Type Name'),
                Forms\Components\TextInput::make('cpu_cores')
                    ->numeric()
                    ->required()
                    ->label('CPU Cores'),
                Forms\Components\TextInput::make('ram')
                    ->numeric()
                    ->required()
                    ->label('RAM (GB)'),
                Forms\Components\TextInput::make('storage')
                    ->numeric()
                    ->required()
                    ->label('Storage (GB)'),
                Forms\Components\TextInput::make('network_speed')
                    ->required()
                    ->label('Network Speed (Gbps)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Type Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cpu_cores')
                    ->label('CPU Cores')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('ram')
                    ->label('RAM (GB)')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('storage')
                    ->label('Storage (GB)')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('network_speed')->label('Network Speed')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('cpu_cores')
                    ->options(ServerType::query()->pluck('cpu_cores', 'cpu_cores')->sort()->toArray())
                    ->placeholder('Filter by CPU Cores'),

                SelectFilter::make('ram')
                    ->options(ServerType::query()->pluck('ram', 'ram')->sort()->toArray())
                    ->placeholder('Filter by RAM'),

                SelectFilter::make('storage')
                    ->options(ServerType::query()->pluck('storage', 'storage')->sort()->toArray())
                    ->placeholder('Filter by Storage'),

                SelectFilter::make('network_speed')
                    ->options(ServerType::query()->pluck('network_speed', 'network_speed')->sort()->toArray())
                    ->placeholder('Filter by Network Speed'),
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
            'index' => Pages\ListServerTypes::route('/'),
            'create' => Pages\CreateServerType::route('/create'),
            'edit' => Pages\EditServerType::route('/{record}/edit'),
        ];
    }
}
