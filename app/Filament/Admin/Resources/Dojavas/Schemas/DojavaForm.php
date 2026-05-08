<?php

namespace App\Filament\Admin\Resources\Dojavas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DojavaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Identifikacija')
                    ->columns(2)
                    ->schema([
                        TextInput::make('broj_dojave')
                            ->label('Broj dojave')
                            ->required()
                            ->maxLength(20)
                            ->placeholder('npr. 2026-001234')
                            ->unique(ignoreRecord: true),

                        Select::make('kanal_dojave')
                            ->label('Kanal dojave')
                            ->required()
                            ->options([
                                '112' => '112',
                                'telefon' => 'Telefon',
                                'osobno' => 'Osobno',
                                'druga_sluzba' => 'Druga služba',
                                'sms_web' => 'SMS / Web',
                            ])
                            ->default('112'),

                        Select::make('dogadjaj_id')
                            ->label('Operativni događaj')
                            ->relationship(
                                'dogadjaj',
                                'naziv',
                                fn ($query) => $query->whereIn('status', ['aktivan', 'pracenje'])
                            )
                            ->preload()
                            ->searchable()
                            ->columnSpanFull()
                            ->placeholder('Bez događaja (samostalna dojava)')
                            ->helperText('Ako je dojava povezana s nekim aktivnim događajem, odaberi ga ovdje'),
                    ]),

                Section::make('Lokacija')
                    ->columns(2)
                    ->schema([
                        TextInput::make('adresa')
                            ->label('Adresa')
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->placeholder('npr. Industrijska 12, Pakrac'),

                        Select::make('jls_id')
                            ->label('JLS')
                            ->relationship('jls', 'naziv', fn ($query) => $query->where('aktivan', true))
                            ->preload()
                            ->searchable()
                            ->required()
                            ->placeholder('Odaberi JLS...'),

                        TextInput::make('opcina')
                            ->label('Općina / Naselje (slobodan unos)')
                            ->maxLength(100)
                            ->placeholder('npr. Pleternica, ako nije glavni grad'),

                        TextInput::make('latitude')
                            ->label('Latitude (GPS)')
                            ->numeric()
                            ->step('any'),

                        TextInput::make('longitude')
                            ->label('Longitude (GPS)')
                            ->numeric()
                            ->step('any'),
                    ]),

                Section::make('Klasifikacija')
                    ->columns(3)
                    ->schema([
                        Select::make('tip_nepogode')
                            ->label('Tip nepogode')
                            ->required()
                            ->options([
                                'olujno_nevrijeme' => 'Olujno nevrijeme',
                                'poplava' => 'Poplava',
                                'pozar' => 'Požar',
                                'snijeg_led' => 'Snijeg / led',
                                'klizište' => 'Klizište',
                                'tuca' => 'Tuča',
                                'potres' => 'Potres',
                                'ostalo' => 'Ostalo',
                            ]),

                        Select::make('ugrozenost_ljudi')
                            ->label('Ugroženost ljudi')
                            ->required()
                            ->options([
                                'da' => 'DA — ima ugroženih',
                                'ne' => 'NE — nema ugroženih',
                                'ne_znam' => 'Ne znam',
                            ])
                            ->default('ne_znam'),

                        Select::make('prioritet')
                            ->label('Prioritet')
                            ->required()
                            ->options([
                                'kriticna' => '🔴 Kritična',
                                'visoka' => '🟡 Visoka',
                                'standardna' => '🟢 Standardna',
                            ])
                            ->default('standardna'),
                    ]),

                Section::make('Opis')
                    ->schema([
                        Textarea::make('opis')
                            ->label('Opis situacije')
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Što se točno dogodilo, što je prijavitelj rekao...'),
                    ]),

                Section::make('Prijavitelj')
                    ->columns(2)
                    ->schema([
                        TextInput::make('prijavitelj_ime')
                            ->label('Ime i prezime')
                            ->maxLength(200),

                        TextInput::make('prijavitelj_telefon')
                            ->label('Telefon')
                            ->tel()
                            ->maxLength(50),

                        Toggle::make('prijavitelj_anoniman')
                            ->label('Anoniman prijavitelj')
                            ->columnSpanFull(),
                    ]),

                Section::make('Status i vremena')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'zaprimljena' => 'Zaprimljena',
                                'dodijeljena' => 'Dodijeljena timu',
                                'u_tijeku' => 'U tijeku',
                                'zavrsena' => 'Završena',
                            ])
                            ->default('zaprimljena'),

                        DateTimePicker::make('vrijeme_zaprimanja')
                            ->label('Vrijeme zaprimanja')
                            ->required()
                            ->default(now()),

                        DateTimePicker::make('vrijeme_zatvaranja')
                            ->label('Vrijeme zatvaranja'),
                    ]),
            ]);
    }
}