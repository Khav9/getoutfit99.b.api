<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                ->required(),

            TextInput::make('email')
                ->email()
                ->required(),

            TextInput::make('password')
                ->password()
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $operation): bool => $operation === 'create'),

            Select::make('roles')
                ->label('Role')
                ->relationship('roles', 'name')
                ->multiple(false)
                ->preload()
                ->searchable(),
            ]);
    }
}
