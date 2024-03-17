<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ServerStatus;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ServerStatusResource\Pages;
use App\Filament\Resources\ServerStatusResource\RelationManagers;
use Filament\Forms\Components\DateTimePicker;

class ServerStatusResource extends Resource
{
    protected static ?string $model = ServerStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subscription_id')
                    ->label('Subscription ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'good',
                        'danger' => 'down',
                        'info' => 'stopped',
                        'gray' => 'terminated',
                    ]),
                Tables\Columns\TextColumn::make('uptime')
                    ->label('Uptime (seconds)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('downtime')
                    ->label('Downtime (seconds)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_started_at')
                    ->dateTime()
                    ->label('Last Started At')
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_stopped_at')
                    ->dateTime()
                    ->label('Last Stopped At')
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_crashed_at')
                    ->dateTime()
                    ->label('Last Crashed At')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'good' => 'Good',
                        'pending' => 'Pending',
                        'down' => 'Down',
                        'stopped' => 'Stopped',
                        'terminated' => 'Terminated',
                    ])
                    ->label('Status Filter'),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                // Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListServerStatuses::route('/'),
            // 'create' => Pages\CreateServerStatus::route('/create'),
            // 'edit' => Pages\EditServerStatus::route('/{record}/edit'),
        ];
    }
}
