<?php

namespace App\Filament\Admin\Resources\Vozilos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VoziloForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Identifikacija')
                    ->columns(2)
                    ->schema([
                        TextInput::make('registracija')
                            ->label('Registracija')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->placeholder('npr. PŽ-123-AB'),

                        TextInput::make('marka')
                            ->label('Marka')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('npr. MAN, Mercedes, Iveco'),

                        TextInput::make('model')
                            ->label('Model')
                            ->maxLength(100)
                            ->placeholder('npr. TGM 13.290, Atego 1530'),

                        TextInput::make('godina_proizvodnje')
                            ->label('Godina proizvodnje')
                            ->numeric()
                            ->minValue(1950)
                            ->maxValue(2030)
                            ->placeholder('npr. 2018'),
                    ]),

                Section::make('Tip i namjena')
                    ->columns(2)
                    ->schema([
                        Select::make('tip')
                            ->label('Tip vozila')
                            ->required()
                            ->options([
                                'navalno' => 'Navalno vozilo',
                                'autocisterna' => 'Autocisterna',
                                'sumsko' => 'Šumsko vozilo',
                                'tehnicko' => 'Tehničko vozilo',
                                'terensko' => 'Terensko vozilo (4x4)',
                                'ljestve' => 'Auto-ljestve',
                                'kombi' => 'Kombi / Putnički',
                                'kamion' => 'Kamion',
                                'ostalo' => 'Ostalo',
                            ]),

                        TextInput::make('namjena')
                            ->label('Dodatna namjena')
                            ->maxLength(100)
                            ->placeholder('npr. Spašavanje iz vode'),

                        Select::make('postrojba_id')
                            ->label('Postrojba')
                            ->relationship('postrojba', 'naziv', fn ($query) => $query->where('aktivna', true))
                            ->preload()
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Tehnički podaci')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('kapacitet_vode')
                            ->label('Kapacitet vode (litara)')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('npr. 4000'),

                        TextInput::make('kapacitet_pjenila')
                            ->label('Kapacitet pjenila (litara)')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('npr. 200'),

                        TextInput::make('broj_sjedala')
                            ->label('Broj sjedala')
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('npr. 6'),

                        TextInput::make('snaga_pumpe')
                            ->label('Snaga pumpe (l/min)')
                            ->numeric()
                            ->minValue(0)
                            ->placeholder('npr. 3000'),
                    ]),

                Section::make('Status i operativnost')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Status vozila')
                            ->required()
                            ->options([
                                'operativno' => 'Operativno',
                                'na_servisu' => 'Na servisu',
                                'u_kvaru' => 'U kvaru',
                                'povučeno' => 'Povučeno iz upotrebe',
                            ])
                            ->default('operativno'),

                        Toggle::make('aktivno')
                            ->label('Aktivno u registru')
                            ->default(true)
                            ->helperText('Neaktivna vozila se ne prikazuju u listama'),

                        DatePicker::make('tehnicki_pregled_do')
                            ->label('Tehnički pregled vrijedi do')
                            ->displayFormat('d.m.Y'),

                        DatePicker::make('registrirano_do')
                            ->label('Registracija vrijedi do')
                            ->displayFormat('d.m.Y'),
                    ]),

                Section::make('Lokacija (opcionalno)')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('latitude')
                            ->label('Latitude')
                            ->numeric()
                            ->step('any'),

                        TextInput::make('longitude')
                            ->label('Longitude')
                            ->numeric()
                            ->step('any'),
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