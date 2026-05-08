<?php

namespace App\Filament\Admin\Resources\OperativniDogadjajs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OperativniDogadjajInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Identifikacija')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('naziv')
                            ->label('Naziv događaja')
                            ->columnSpanFull()
                            ->weight('bold')
                            ->size('lg'),

                        TextEntry::make('tip_nepogode')
                            ->label('Tip nepogode')
                            ->formatStateUsing(fn (string $state) => match ($state) {
                                'olujno_nevrijeme' => 'Olujno nevrijeme',
                                'poplava' => 'Poplava',
                                'pozar' => 'Požar',
                                'snijeg_led' => 'Snijeg / led',
                                'klizište' => 'Klizište',
                                'tuca' => 'Tuča',
                                'potres' => 'Potres',
                                'ostalo' => 'Ostalo',
                                default => $state,
                            }),

                        TextEntry::make('stupanj_sukoba')
                            ->label('Stupanj sukoba')
                            ->placeholder('-')
                            ->badge(),

                        TextEntry::make('opis')
                            ->label('Opis')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                Section::make('Razina i pripadnost')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('razina')
                            ->label('Razina')
                            ->badge()
                            ->color(fn (string $state) => match ($state) {
                                'lokalna' => 'info',
                                'zupanijska' => 'warning',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state) => match ($state) {
                                'lokalna' => 'Lokalna',
                                'zupanijska' => 'Županijska',
                                default => $state,
                            }),

                        TextEntry::make('jls.naziv')
                            ->label('JLS')
                            ->placeholder('Županijska razina'),
                    ]),

                Section::make('Vremena i status')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state) => match ($state) {
                                'pracenje' => 'info',
                                'aktivan' => 'danger',
                                'zatvoren' => 'gray',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state) => match ($state) {
                                'pracenje' => 'Praćenje',
                                'aktivan' => 'Aktivan',
                                'zatvoren' => 'Zatvoren',
                                default => $state,
                            }),

                        TextEntry::make('voditelj.name')
                            ->label('Voditelj događaja')
                            ->placeholder('-'),

                        TextEntry::make('vrijeme_otvaranja')
                            ->label('Vrijeme otvaranja')
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('vrijeme_zatvaranja')
                            ->label('Vrijeme zatvaranja')
                            ->dateTime('d.m.Y H:i')
                            ->placeholder('Događaj još traje'),
                    ]),

                Section::make('Audit')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextEntry::make('otvorio.name')
                            ->label('Otvorio')
                            ->placeholder('-'),

                        TextEntry::make('zatvorio.name')
                            ->label('Zatvorio')
                            ->placeholder('-'),

                        TextEntry::make('created_at')
                            ->label('Kreiran u sustavu')
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Zadnje ažuriranje')
                            ->dateTime('d.m.Y H:i'),
                    ]),

                Section::make('Završni sažetak')
                    ->visible(fn ($record) => filled($record?->zavrsni_sazetak))
                    ->schema([
                        TextEntry::make('zavrsni_sazetak')
                            ->label('Sažetak događaja')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}