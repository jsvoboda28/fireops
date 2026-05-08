<?php

namespace App\Filament\Admin\Resources\Postrojbas\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostrojbaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Identifikacija')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('naziv')
                            ->label('Puni naziv')
                            ->columnSpanFull()
                            ->weight('bold')
                            ->size('lg'),

                        TextEntry::make('skraceni_naziv')
                            ->label('Skraćeni naziv')
                            ->placeholder('-'),

                        TextEntry::make('tip')
                            ->label('Tip')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => match ($state) {
                                'dvd' => 'DVD',
                                'jvp' => 'JVP',
                                'pvpp' => 'PVPP',
                                'ostalo' => 'Ostalo',
                                default => $state,
                            }),

                        TextEntry::make('kategorija')
                            ->label('Kategorija')
                            ->placeholder('-')
                            ->badge(),

                        TextEntry::make('jls.naziv')
                            ->label('Pripadnost JLS-u'),
                    ]),

                Section::make('Identifikacijski podaci')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextEntry::make('oib')
                            ->label('OIB')
                            ->placeholder('-')
                            ->copyable(),

                        TextEntry::make('mb')
                            ->label('Matični broj')
                            ->placeholder('-'),
                    ]),

                Section::make('Adresa i lokacija')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('adresa')
                            ->label('Adresa')
                            ->placeholder('-'),

                        TextEntry::make('mjesto')
                            ->label('Mjesto')
                            ->placeholder('-'),

                        TextEntry::make('latitude')
                            ->label('Latitude')
                            ->placeholder('-'),

                        TextEntry::make('longitude')
                            ->label('Longitude')
                            ->placeholder('-'),
                    ]),

                Section::make('Kontakt')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('telefon')
                            ->label('Telefon')
                            ->placeholder('-')
                            ->copyable(),

                        TextEntry::make('mobitel')
                            ->label('Mobitel')
                            ->placeholder('-')
                            ->copyable(),

                        TextEntry::make('email')
                            ->label('Email')
                            ->placeholder('-')
                            ->copyable(),

                        TextEntry::make('web')
                            ->label('Web')
                            ->placeholder('-'),
                    ]),

                Section::make('Operativno stanje')
                    ->columns(2)
                    ->schema([
                        IconEntry::make('aktivna')
                            ->label('Aktivna')
                            ->boolean(),

                        IconEntry::make('operativno_spremna')
                            ->label('Operativno spremna')
                            ->boolean(),

                        TextEntry::make('ukupno_clanova')
                            ->label('Ukupno članova')
                            ->state(fn ($record) => $record->vatrogasci()->count())
                            ->badge()
                            ->color('gray'),

                        TextEntry::make('ukupno_operativnih')
                            ->label('Operativni članovi')
                            ->state(fn ($record) => $record->vatrogasci()
                                ->where('operativan', true)
                                ->where('status', 'aktivan')
                                ->count())
                            ->badge()
                            ->color('success'),
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