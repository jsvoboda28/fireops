<?php

namespace App\Filament\Admin\Resources\Postrojbas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostrojbaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Identifikacija')
                    ->columns(2)
                    ->schema([
                        TextInput::make('naziv')
                            ->label('Puni naziv')
                            ->required()
                            ->maxLength(200)
                            ->placeholder('npr. Dobrovoljno vatrogasno društvo Pakrac'),

                        TextInput::make('skraceni_naziv')
                            ->label('Skraćeni naziv')
                            ->maxLength(50)
                            ->placeholder('npr. DVD Pakrac'),

                        Select::make('tip')
                            ->label('Tip postrojbe')
                            ->required()
                            ->options([
                                'dvd' => 'DVD - Dobrovoljno vatrogasno društvo',
                                'jvp' => 'JVP - Javna vatrogasna postrojba',
                                'pvpp' => 'PVPP - Profesionalna vatrogasna postrojba u poduzeću',
                                'ostalo' => 'Ostalo',
                            ])
                            ->live(),

                        Select::make('kategorija')
                            ->label('Kategorija (DVD)')
                            ->options([
                                'I' => 'I. kategorija',
                                'II' => 'II. kategorija',
                                'III' => 'III. kategorija',
                            ])
                            ->placeholder('Odaberi kategoriju...')
                            ->visible(fn (callable $get) => $get('tip') === 'dvd')
                            ->helperText('Kategorija prema operativnoj sposobnosti'),

                        Select::make('jls_id')
                            ->label('Pripadnost JLS-u')
                            ->relationship('jls', 'naziv', fn ($query) => $query->where('aktivan', true))
                            ->preload()
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Identifikacijski podaci')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('oib')
                            ->label('OIB')
                            ->maxLength(11)
                            ->minLength(11),

                        TextInput::make('mb')
                            ->label('Matični broj')
                            ->maxLength(20),
                    ]),

                Section::make('Adresa i lokacija')
                    ->columns(2)
                    ->schema([
                        TextInput::make('adresa')
                            ->label('Adresa')
                            ->maxLength(200)
                            ->placeholder('npr. Kralja Tomislava 12'),

                        TextInput::make('mjesto')
                            ->label('Mjesto')
                            ->maxLength(100)
                            ->placeholder('npr. Pakrac'),

                        TextInput::make('latitude')
                            ->label('Latitude (GPS)')
                            ->numeric()
                            ->step('any')
                            ->helperText('Za prikaz na karti'),

                        TextInput::make('longitude')
                            ->label('Longitude (GPS)')
                            ->numeric()
                            ->step('any'),
                    ]),

                Section::make('Kontakt')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('telefon')
                            ->label('Telefon (fiksni)')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('mobitel')
                            ->label('Mobitel')
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
                            ->placeholder('https://...'),
                    ]),

                Section::make('Operativno stanje')
                    ->columns(2)
                    ->schema([
                        Toggle::make('aktivna')
                            ->label('Postrojba je aktivna')
                            ->default(true)
                            ->helperText('Neaktivne se ne pojavljuju u listama za odabir'),

                        Toggle::make('operativno_spremna')
                            ->label('Operativno spremna')
                            ->default(true)
                            ->helperText('Trenutna spremnost za intervencije'),

                        TextInput::make('broj_clanova')
                            ->label('Ukupan broj članova')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),

                        TextInput::make('broj_operativnih')
                            ->label('Broj operativnih članova')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Članovi sposobni za intervencije'),
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