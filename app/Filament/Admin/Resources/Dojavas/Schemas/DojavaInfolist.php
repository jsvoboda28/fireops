<?php

namespace App\Filament\Admin\Resources\Dojavas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DojavaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Identifikacija')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('broj_dojave')->label('Broj dojave'),
                        TextEntry::make('kanal_dojave')->label('Kanal dojave'),
                    ]),

                Section::make('Lokacija')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('adresa')->label('Adresa')->columnSpanFull(),
                        TextEntry::make('opcina')->label('Općina / Grad'),
                        TextEntry::make('latitude')->label('Latitude'),
                        TextEntry::make('longitude')->label('Longitude'),
                    ]),

                Section::make('Klasifikacija')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('tip_nepogode')->label('Tip nepogode'),
                        TextEntry::make('ugrozenost_ljudi')->label('Ugroženost'),
                        TextEntry::make('prioritet')
                            ->label('Prioritet')
                            ->badge()
                            ->color(fn (string $state) => match ($state) {
                                'kriticna' => 'danger',
                                'visoka' => 'warning',
                                'standardna' => 'success',
                                default => 'gray',
                            }),
                    ]),

                Section::make('Opis')
                    ->schema([
                        TextEntry::make('opis')->label('Opis situacije')->columnSpanFull(),
                    ]),

                Section::make('Prijavitelj')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('prijavitelj_ime')->label('Ime'),
                        TextEntry::make('prijavitelj_telefon')->label('Telefon'),
                    ]),

                Section::make('Status')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('status')->label('Status')->badge(),
                        TextEntry::make('vrijeme_zaprimanja')->label('Zaprimljeno')->dateTime('d.m.Y H:i'),
                        TextEntry::make('vrijeme_zatvaranja')->label('Zatvoreno')->dateTime('d.m.Y H:i'),
                    ]),
            ]);
    }
}