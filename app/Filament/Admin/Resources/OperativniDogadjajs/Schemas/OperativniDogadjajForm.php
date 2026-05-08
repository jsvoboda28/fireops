<?php

namespace App\Filament\Admin\Resources\OperativniDogadjajs\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OperativniDogadjajForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Identifikacija događaja')
                    ->columns(2)
                    ->schema([
                        TextInput::make('naziv')
                            ->label('Naziv događaja')
                            ->required()
                            ->maxLength(200)
                            ->columnSpanFull()
                            ->placeholder('npr. Olujno nevrijeme nad Pakracom'),

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

                        Select::make('stupanj_sukoba')
                            ->label('Stupanj sukoba')
                            ->options([
                                'I' => 'I — Spremnost',
                                'II' => 'II — Pripravnost',
                                'III' => 'III — Smanjeni stupanj',
                                'IV' => 'IV — Najviši stupanj',
                            ])
                            ->placeholder('Odaberi stupanj...')
                            ->helperText('Stupnjevi sukoba prema operativnom planu'),

                        Textarea::make('opis')
                            ->label('Opis događaja')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Kratak opis - što se događa, gdje, koje su prve procjene...'),
                    ]),

                Section::make('Razina i pripadnost')
                    ->columns(2)
                    ->schema([
                        Select::make('razina')
                            ->label('Razina događaja')
                            ->required()
                            ->options([
                                'lokalna' => 'Lokalna (JLS)',
                                'zupanijska' => 'Županijska',
                            ])
                            ->default('lokalna')
                            ->live()
                            ->helperText('Lokalni događaj pripada jednom JLS-u, županijski pokriva više njih'),

                        Select::make('jls_id')
                            ->label('JLS')
                            ->relationship('jls', 'naziv', fn ($query) => $query->where('aktivan', true))
                            ->preload()
                            ->searchable()
                            ->required(fn (callable $get) => $get('razina') === 'lokalna')
                            ->visible(fn (callable $get) => $get('razina') === 'lokalna')
                            ->placeholder('Odaberi JLS...'),
                    ]),

                Section::make('Vremena i status')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Status događaja')
                            ->required()
                            ->options([
                                'pracenje' => '🔵 Praćenje (early warning)',
                                'aktivan' => '🔴 Aktivan',
                                'zatvoren' => '⚫ Zatvoren',
                            ])
                            ->default('aktivan'),

                        Select::make('voditelj_id')
                            ->label('Voditelj događaja')
                            ->relationship('voditelj', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Odaberi voditelja...')
                            ->helperText('Tko je glavni operativni voditelj događaja'),

                        DateTimePicker::make('vrijeme_otvaranja')
                            ->label('Vrijeme otvaranja')
                            ->required()
                            ->default(now())
                            ->seconds(false),

                        DateTimePicker::make('vrijeme_zatvaranja')
                            ->label('Vrijeme zatvaranja')
                            ->seconds(false)
                            ->helperText('Ostavi prazno dok je događaj aktivan'),
                    ]),

                Section::make('Završni sažetak')
                    ->collapsed()
                    ->schema([
                        Textarea::make('zavrsni_sazetak')
                            ->label('Završni sažetak')
                            ->rows(5)
                            ->columnSpanFull()
                            ->placeholder('Popunjava se kad se događaj zatvara — što se dogodilo, koliko je trajalo, najveće lekcije...'),
                    ]),
            ]);
    }
}