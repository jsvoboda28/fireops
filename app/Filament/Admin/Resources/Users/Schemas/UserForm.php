<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Osnovni podaci')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Ime i prezime')
                            ->required()
                            ->maxLength(200)
                            ->placeholder('npr. Josip Svoboda'),

                        TextInput::make('email')
                            ->label('Email adresa')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(200)
                            ->placeholder('npr. ime.prezime@dvd-pakrac.hr'),
                    ]),

                Section::make('Lozinka')
                    ->columns(2)
                    ->schema([
                        TextInput::make('password')
                            ->label('Lozinka')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->minLength(8)
                            ->helperText(fn (string $operation): string => 
                                $operation === 'create' 
                                    ? 'Minimum 8 znakova' 
                                    : 'Ostavi prazno ako ne želiš mijenjati'
                            ),

                        TextInput::make('password_confirmation')
                            ->label('Potvrdi lozinku')
                            ->password()
                            ->revealable()
                            ->same('password')
                            ->dehydrated(false)
                            ->required(fn (string $operation): bool => $operation === 'create'),
                    ]),

                Section::make('Uloge i dozvole')
                    ->schema([
                        Select::make('roles')
                            ->label('Uloge')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable()
                            ->helperText('Odaberi jednu ili više uloga koje korisnik ima u sustavu')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}