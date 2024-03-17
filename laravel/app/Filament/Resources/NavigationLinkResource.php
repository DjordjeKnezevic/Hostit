<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NavigationLinkResource\Pages;
use App\Filament\Resources\NavigationLinkResource\RelationManagers;
use App\Models\NavigationLink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NavigationLinkResource extends Resource
{
    protected static ?string $model = NavigationLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Name'),
                Forms\Components\TextInput::make('route')
                    ->required()
                    ->label('Route'),
                Forms\Components\FileUpload::make('icon')
                    ->image()
                    ->directory('img')
                    ->label('Icon'),
                Forms\Components\Toggle::make('is_navbar')
                    ->label('Is Navbar?')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('route')
                    ->label('Route')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('icon')
                    ->label('Icon')
                    ->checkFileExistence(false)
                    ->url(fn (NavigationLink $record): ?string => $record->icon_url)
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_navbar')
                    ->boolean()
                    ->label('Is Navbar?'),
            ])
            ->filters([
                Tables\Filters\Filter::make('navbar_links')
                    ->label('Navbar Only Links')
                    ->query(fn ($query) => $query->where('is_navbar', true)),
                Tables\Filters\Filter::make('non_navbar_links')
                    ->label('Non-Navbar Links')
                    ->query(fn ($query) => $query->where('is_navbar', false)),
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
            'index' => Pages\ListNavigationLinks::route('/'),
            'create' => Pages\CreateNavigationLink::route('/create'),
            'edit' => Pages\EditNavigationLink::route('/{record}/edit'),
        ];
    }
}
