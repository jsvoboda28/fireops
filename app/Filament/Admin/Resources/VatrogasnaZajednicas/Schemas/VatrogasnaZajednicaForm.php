<?php

namespace App\Filament\Admin\Resources\VatrogasnaZajednicas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VatrogasnaZajednicaForm
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
                            ->columnSpanFull(),

                        TextInput::make('skraceni_naziv')
                            ->label('Skraćeni naziv')
                            ->maxLength(50)
                            ->placeholder('npr. VZ PSŽ, VZP Pakrac-Lipik'),

                        Select::make('tip')
                            ->label('Tip vatrogasne zajednice')
                            ->required()
                            ->options([
                                'zupanijska' => 'Županijska VZ',
                                'podrucna' => 'Područna VZ (VZP/VZG)',
                                'opcinska' => 'Općinska VZ',
                            ]),
                    ]),

                Section::make('Hijerarhija')
                    ->schema([
                        Select::make('roditelj_id')
                            ->label('Roditeljska VZ')
                            ->relationship('roditelj', 'naziv')
                            ->preload()
                            ->searchable()
                            ->placeholder('Bez roditelja (županijska razina)')
                            ->helperText('Područne VZ-e biraju županijsku VZ kao roditelja'),

                        Select::make('jlsovi')
                            ->label('Pokrivene JLS')
                            ->multiple()
                            ->relationship('jlsovi', 'naziv')
                            ->preload()
                            ->searchable()
                            ->helperText('JLS-ovi koje ova VZ pokriva (samo za područne VZ-e)'),
                    ]),

                Section::make('Kontakt')
                    ->columns(2)
                    ->schema([
                        TextInput::make('oib')
                            ->label('OIB')
                            ->maxLength(11)
                            ->placeholder('11 znamenki'),

                        TextInput::make('adresa')
                            ->label('Adresa')
                            ->maxLength(300),

                        TextInput::make('telefon')
                            ->label('Telefon')
                            ->tel(),

                        TextInput::make('email')
                            ->label('Email')
                            ->email(),

                        TextInput::make('web')
                            ->label('Web stranica')
                            ->columnSpanFull(),
                    ]),

                Section::make('Status')
                    ->schema([
                        Toggle::make('aktivna')
                            ->label('VZ je aktivna')
                            ->default(true),

                        Textarea::make('napomena')
                            ->label('Napomena')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}