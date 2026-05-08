<?php

namespace App\Filament\Admin\Resources\Vatrogasacs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class VatrogasacForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Osobni podaci')
                    ->columns(2)
                    ->schema([
                        TextInput::make('ime')
                            ->label('Ime')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('prezime')
                            ->label('Prezime')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('oib')
                            ->label('OIB')
                            ->maxLength(11)
                            ->minLength(11)
                            ->unique(ignoreRecord: true)
                            ->placeholder('11 znamenki'),

                        DatePicker::make('datum_rodjenja')
                            ->label('Datum rođenja')
                            ->displayFormat('d.m.Y'),
                    ]),

                Section::make('Pripadnost i kategorija')
                    ->columns(2)
                    ->schema([
                        Select::make('postrojba_id')
                            ->label('Postrojba')
                            ->relationship('postrojba', 'naziv', fn ($query) => $query->where('aktivna', true))
                            ->preload()
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),

                        Select::make('kategorija')
                            ->label('Kategorija osposobljenosti')
                            ->required()
                            ->options([
                                'vatrogasac' => 'Vatrogasac',
                                'vatrogasac_I' => 'Vatrogasac I. klase',
                                'vatrogasac_II' => 'Vatrogasac II. klase',
                                'docasnik' => 'Dočasnik',
                                'docasnik_I' => 'Dočasnik I. klase',
                                'casnik' => 'Časnik',
                                'casnik_I' => 'Časnik I. klase',
                                'visi_casnik' => 'Viši časnik',
                            ])
                            ->default('vatrogasac'),

                        Select::make('specijalnosti')
                            ->label('Specijalnosti')
                            ->multiple()
                            ->options([
                                'vozac' => 'Vozač C kategorije',
                                'vozac_d' => 'Vozač D kategorije',
                                'ronilac' => 'Ronilac',
                                'alpinist' => 'Alpinist',
                                'kemicar' => 'Kemičar (HAZMAT)',
                                'spasitelj' => 'Spasitelj iz vode',
                                'pilot_drona' => 'Pilot drona',
                                'strojar' => 'Strojar',
                                'instruktor' => 'Instruktor',
                                'lijecnik' => 'Liječnik / medicinar',
                            ])
                            ->placeholder('Odaberi specijalnosti...'),
                    ]),

                Section::make('Kontakt')
                    ->columns(2)
                    ->schema([
                        TextInput::make('mobitel')
                            ->label('Mobitel')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(200),
                    ]),

                Section::make('PIN za mobilnu aplikaciju')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('pin_hash')
                            ->label('PIN (4-6 znamenki)')
                            ->password()
                            ->revealable()
                            ->minLength(4)
                            ->maxLength(6)
                            ->numeric()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(false)
                            ->helperText(fn (string $operation) => 
                                $operation === 'create' 
                                    ? 'PIN se koristi za prijavu u mobilnu aplikaciju' 
                                    : 'Ostavi prazno ako ne želiš mijenjati postojeći PIN'
                            ),
                    ]),

                Section::make('Status i operativnost')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'aktivan' => 'Aktivan',
                                'mirovanje' => 'U mirovanju',
                                'neaktivan' => 'Neaktivan',
                                'suspenzija' => 'Suspenzija',
                            ])
                            ->default('aktivan'),

                        Toggle::make('operativan')
                            ->label('Operativan')
                            ->default(true)
                            ->helperText('Može sudjelovati u intervencijama'),

                        DatePicker::make('datum_pristupa')
                            ->label('Datum pristupa postrojbi')
                            ->displayFormat('d.m.Y'),
                    ]),

                Section::make('Napomena')
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