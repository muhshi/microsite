<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengguna')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required(),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('nip')
                            ->label('NIP')
                            ->nullable(),

                        TextInput::make('jabatan')
                            ->label('Jabatan')
                            ->nullable(),

                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->placeholder(fn (string $context): string => $context === 'edit' ? 'Biarkan kosong jika tidak ingin mengubah password' : 'Masukkan password'),
                    ])
                    ->columns(2),

                Section::make('Hak Akses (Roles)')
                    ->schema([
                        CheckboxList::make('roles')
                            ->label('Roles')
                            ->relationship('roles', 'name')
                            ->preload()
                            ->columns(2)
                            ->required(),
                    ]),
            ]);
    }
}
