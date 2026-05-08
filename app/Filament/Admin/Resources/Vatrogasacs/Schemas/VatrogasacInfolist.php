<?php

namespace App\Filament\Admin\Resources\Vatrogasacs\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VatrogasacInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Osobni podaci')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('ime')
                            ->label('Ime'),

                        TextEntry::make('prezime')
                            ->label('Prezime'),

                        TextEntry::make('oib')
                            ->label('OIB')
                            ->placeholder('-')
                            ->copyable(),

                        TextEntry::make('datum_rodjenja')
                            ->label('Datum rođenja')
                            ->date('d.m.Y')
                            ->placeholder('-'),
                    ]),

                Section::make('Pripadnost')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('postrojba.naziv')
                            ->label('Postrojba')
                            ->placeholder('-'),

                        TextEntry::make('kategorija')
                            ->label('Kategorija')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => match ($state) {
                                'vatrogasac' => 'Vatrogasac',
                                'vatrogasac_I' => 'Vatrogasac I.',
                                'vatrogasac_II' => 'Vatrogasac II.',
                                'docasnik' => 'Dočasnik',
                                'docasnik_I' => 'Dočasnik I.',
                                'casnik' => 'Časnik',
                                'casnik_I' => 'Časnik I.',
                                'visi_casnik' => 'Viši časnik',
                                default => $state,
                            }),

                        TextEntry::make('specijalnosti')
                            ->label('Specijalnosti')
                            ->placeholder('-')
                            ->columnSpanFull()
                            ->badge()
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'vozac' => 'Vozač C',
                                'vozac_d' => 'Vozač D',
                                'ronilac' => 'Ronilac',
                                'alpinist' => 'Alpinist',
                                'kemicar' => 'Kemičar',
                                'spasitelj' => 'Spasitelj iz vode',
                                'pilot_drona' => 'Pilot drona',
                                'strojar' => 'Strojar',
                                'instruktor' => 'Instruktor',
                                'lijecnik' => 'Liječnik',
                                default => $state,
                            }),
                    ]),

                Section::make('Kontakt')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('mobitel')
                            ->label('Mobitel')
                            ->placeholder('-')
                            ->copyable(),

                        TextEntry::make('email')
                            ->label('Email')
                            ->placeholder('-')
                            ->copyable(),
                    ]),

                Section::make('Status')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(fn (string $state) => match ($state) {
                                'aktivan' => 'Aktivan',
                                'mirovanje' => 'Mirovanje',
                                'neaktivan' => 'Neaktivan',
                                'suspenzija' => 'Suspenzija',
                                default => $state,
                            }),

                        IconEntry::make('operativan')
                            ->label('Operativan')
                            ->boolean(),

                        TextEntry::make('datum_pristupa')
                            ->label('Datum pristupa postrojbi')
                            ->date('d.m.Y')
                            ->placeholder('-'),

                        TextEntry::make('pin_hash')
                            ->label('PIN postavljen')
                            ->placeholder('Ne')
                            ->formatStateUsing(fn ($state) => filled($state) ? '✓ Da' : 'Ne'),
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