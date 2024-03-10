<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->minLength(6)
                    ->visible(fn ($livewire) => $livewire instanceof CreateUser)
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                Forms\Components\Select::make('role_id')
                    ->relationship('role', 'name')
                    ->required(),
                Forms\Components\Toggle::make('email_verified_at')
                    ->label('Email Verified')
                    ->reactive()
                    ->columnSpan(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role.name')->label('Role')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Verified Email')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->email_verified_at !== null),
                Tables\Columns\IconColumn::make('remember_token')
                    ->label('Remembered')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->remember_token !== null)
            ])
            ->filters([
                Tables\Filters\Filter::make('Verified')
                    ->query(fn ($query) => $query->whereNotNull('email_verified_at')),
                Tables\Filters\Filter::make('Unverified')
                    ->query(fn ($query) => $query->whereNull('email_verified_at')),
                Tables\Filters\Filter::make('Remembered')
                    ->query(fn ($query) => $query->whereNotNull('remember_token')),
                Tables\Filters\Filter::make('Not Remembered')
                    ->query(fn ($query) => $query->whereNull('remember_token')),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
