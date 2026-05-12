<?php

namespace App\Filament\Admin\Resources\VatrogasnaZajednicas\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VatrogasnaZajednicaInfolist
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
                                'zupanijska' => 'Županijska',
                                'podrucna' => 'Područna',
                                'opcinska' => 'Općinska',
                                default => $state,
                            }),
                    ]),

                Section::make('Hijerarhija i pokrivenost')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('roditelj.naziv')
                            ->label('Pripada')
                            ->placeholder('Bez roditelja (županijska)'),

                        TextEntry::make('jlsovi.naziv')
                            ->label('Pokrivene JLS')
                            ->placeholder('-')
                            ->columnSpanFull()
                            ->badge()
                            ->color('info'),
                    ]),

                Section::make('Kontakt')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('oib')
                            ->label('OIB')
                            ->placeholder('-')
                            ->copyable(),

                        TextEntry::make('adresa')
                            ->label('Adresa')
                            ->placeholder('-'),

                        TextEntry::make('telefon')
                            ->label('Telefon')
                            ->placeholder('-')
                            ->copyable(),

                        TextEntry::make('email')
                            ->label('Email')
                            ->placeholder('-')
                            ->copyable(),

                        TextEntry::make('web')
                            ->label('Web')
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                Section::make('Status')
                    ->columns(2)
                    ->schema([
                        IconEntry::make('aktivna')
                            ->label('Aktivna')
                            ->boolean(),
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