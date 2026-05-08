<?php

namespace App\Filament\Admin\Resources\Jls\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class JlsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Osnovni podaci')
                    ->columns(2)
                    ->schema([
                        TextInput::make('naziv')
                            ->label('Naziv')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('npr. Pakrac'),

                        Select::make('tip')
                            ->label('Tip JLS-a')
                            ->required()
                            ->options([
                                'grad' => 'Grad',
                                'opcina' => 'Općina',
                            ]),

                        TextInput::make('zupanija')
                            ->label('Županija')
                            ->required()
                            ->default('Požeško-slavonska')
                            ->maxLength(100),

                        Toggle::make('aktivan')
                            ->label('Aktivan')
                            ->default(true)
                            ->helperText('Aktivni JLS-ovi se prikazuju u listama za odabir'),
                    ]),

                Section::make('Identifikacija')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('mb')
                            ->label('Matični broj')
                            ->maxLength(20),

                        TextInput::make('oib')
                            ->label('OIB')
                            ->maxLength(11)
                            ->minLength(11),
                    ]),

                Section::make('Kontakt')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('adresa')
                            ->label('Adresa')
                            ->maxLength(200)
                            ->columnSpanFull(),

                        TextInput::make('telefon')
                            ->label('Telefon')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(200),

                        TextInput::make('web')
                            ->label('Web stranica')
                            ->url()
                            ->maxLength(200)
                            ->columnSpanFull()
                            ->placeholder('https://...'),
                    ]),

                Section::make('Napomene')
                    ->collapsed()
                    ->schema([
                        Textarea::make('napomena')
                            ->label('Napomena')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}