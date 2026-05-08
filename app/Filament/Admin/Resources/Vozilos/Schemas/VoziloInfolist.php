<?php

namespace App\Filament\Admin\Resources\Vozilos\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VoziloInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Identifikacija')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('registracija')
                            ->label('Registracija')
                            ->weight('bold')
                            ->size('lg')
                            ->copyable(),

                        TextEntry::make('marka')
                            ->label('Marka'),

                        TextEntry::make('model')
                            ->label('Model')
                            ->placeholder('-'),

                        TextEntry::make('godina_proizvodnje')
                            ->label('Godina proizvodnje')
                            ->placeholder('-'),
                    ]),

                Section::make('Tip i pripadnost')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('tip')
                            ->label('Tip vozila')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => match ($state) {
                                'navalno' => 'Navalno',
                                'autocisterna' => 'Autocisterna',
                                'sumsko' => 'Šumsko',
                                'tehnicko' => 'Tehničko',
                                'terensko' => 'Terensko 4x4',
                                'ljestve' => 'Ljestve',
                                'kombi' => 'Kombi',
                                'kamion' => 'Kamion',
                                'ostalo' => 'Ostalo',
                                default => $state,
                            }),

                        TextEntry::make('namjena')
                            ->label('Dodatna namjena')
                            ->placeholder('-'),

                        TextEntry::make('postrojba.naziv')
                            ->label('Postrojba')
                            ->columnSpanFull(),
                    ]),

                Section::make('Tehnički podaci')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('kapacitet_vode')
                            ->label('Kapacitet vode')
                            ->placeholder('-')
                            ->suffix(' L'),

                        TextEntry::make('kapacitet_pjenila')
                            ->label('Kapacitet pjenila')
                            ->placeholder('-')
                            ->suffix(' L'),

                        TextEntry::make('broj_sjedala')
                            ->label('Broj sjedala')
                            ->placeholder('-'),

                        TextEntry::make('snaga_pumpe')
                            ->label('Snaga pumpe')
                            ->placeholder('-')
                            ->suffix(' l/min'),
                    ]),

                Section::make('Status')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => match ($state) {
                                'operativno' => 'Operativno',
                                'na_servisu' => 'Na servisu',
                                'u_kvaru' => 'U kvaru',
                                'povučeno' => 'Povučeno',
                                default => $state,
                            }),

                        IconEntry::make('aktivno')
                            ->label('Aktivno')
                            ->boolean(),

                        TextEntry::make('tehnicki_pregled_do')
                            ->label('Tehnički pregled vrijedi do')
                            ->date('d.m.Y')
                            ->placeholder('-')
                            ->color(fn ($state) => match (true) {
                                !$state => 'gray',
                                $state->isPast() => 'danger',
                                $state->diffInDays(now()) <= 30 => 'warning',
                                default => 'success',
                            }),

                        TextEntry::make('registrirano_do')
                            ->label('Registracija vrijedi do')
                            ->date('d.m.Y')
                            ->placeholder('-')
                            ->color(fn ($state) => match (true) {
                                !$state => 'gray',
                                $state->isPast() => 'danger',
                                $state->diffInDays(now()) <= 30 => 'warning',
                                default => 'success',
                            }),
                    ]),

                Section::make('Napomena')
                    ->visible(fn ($record) => filled($record?->napomena))
                    ->schema([
                        TextEntry::make('napomena')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}