<?php

namespace App\Filament\Admin\Resources\Intervencijas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class IntervencijaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                Section::make('Osnovni podaci')
                    ->columns(2)
                    ->schema([
                        TextInput::make('broj')
                            ->label('Broj intervencije')
                            ->maxLength(50)
                            ->placeholder('Auto-generira se ako je prazno')
                            ->helperText('Npr. 2026-INT-001234')
                            ->unique(ignoreRecord: true),
                        
                        Select::make('tip_intervencije')
                            ->label('Tip intervencije')
                            ->required()
                            ->options([
                                'pozar' => '🔥 Požar',
                                'poplava' => '🌊 Poplava',
                                'tehnicka' => '🔧 Tehnička intervencija',
                                'prometna' => '🚗 Prometna nesreća',
                                'spasavanje' => '⛑ Spašavanje',
                                'opasne_tvari' => '☣ Opasne tvari',
                                'ostalo' => '❓ Ostalo',
                            ])
                            ->default('pozar'),

                        TextInput::make('naziv')
                            ->label('Naziv intervencije')
                            ->required()
                            ->maxLength(300)
                            ->columnSpanFull()
                            ->placeholder('npr. Požar u Industrijskoj ulici, Pakrac'),

                        Textarea::make('opis')
                            ->label('Opis')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Lokacija')
                    ->columns(2)
                    ->schema([
                        TextInput::make('adresa')
                            ->label('Adresa')
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Select::make('jls_id')
                            ->label('JLS')
                            ->relationship('jls', 'naziv', fn ($query) => $query->where('aktivan', true))
                            ->preload()
                            ->searchable(),

                        TextInput::make('latitude')
                            ->label('Latitude (GPS)')
                            ->numeric()
                            ->step('any'),

                        TextInput::make('longitude')
                            ->label('Longitude (GPS)')
                            ->numeric()
                            ->step('any'),
                    ]),

                Section::make('Status i prioritet')
                    ->columns(3)
                    ->schema([
                        Select::make('prioritet')
                            ->label('Prioritet')
                            ->required()
                            ->options([
                                'kriticna' => '🔴 Kritična',
                                'visoka' => '🟡 Visoka',
                                'standardna' => '🟢 Standardna',
                            ])
                            ->default('standardna'),

                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'aktivna' => 'Aktivna',
                                'zatvorena' => 'Zatvorena',
                                'otkazana' => 'Otkazana',
                            ])
                            ->default('aktivna'),

                        Select::make('voditelj_id')
                            ->label('Voditelj intervencije')
                            ->relationship('voditelj', 'name')
                            ->searchable()
                            ->preload(),
                    ]),

                Section::make('Vremena')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('vrijeme_otvaranja')
                            ->label('Vrijeme otvaranja')
                            ->default(now()),

                        DateTimePicker::make('vrijeme_zatvaranja')
                            ->label('Vrijeme zatvaranja'),
                    ]),

                Section::make('Veze')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        Select::make('operativni_dogadjaj_id')
                            ->label('Operativni događaj')
                            ->relationship(
                                'operativniDogadjaj',
                                'naziv',
                                fn ($query) => $query->whereIn('status', ['aktivan', 'pracenje'])
                            )
                            ->preload()
                            ->searchable()
                            ->helperText('Ako je dio veće elementarne nepogode'),

                        Select::make('pocetna_dojava_id')
                            ->label('Početna dojava')
                            ->relationship('pocetnaDojava', 'broj_dojave')
                            ->preload()
                            ->searchable(),
                    ]),
            ]);
    }
}