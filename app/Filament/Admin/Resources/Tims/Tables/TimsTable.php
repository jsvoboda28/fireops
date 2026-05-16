<?php

namespace App\Filament\Admin\Resources\Tims\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TimsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('naziv')
                    ->label('Naziv')
                    ->searchable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('trenutni_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'formiran' => '🆕 Formiran',
                        'polazak' => '🚒 Polazak',
                        'na_mjestu' => '📍 Na mjestu',
                        'intervencija_zavrsena' => '✅ Završili',
                        'povratak' => '↩️ Povratak',
                        'odmor' => '😴 Odmor',
                        'cekanje_u_bazi' => '🏠 U bazi',
                        'raspusten' => '🚪 Raspušten',
                        default => $state,
                    })
                    ->color(fn ($state) => match($state) {
                        'formiran' => 'warning',
                        'polazak' => 'info',
                        'na_mjestu' => 'danger',
                        'intervencija_zavrsena' => 'success',
                        'povratak' => 'info',
                        'odmor' => 'gray',
                        'cekanje_u_bazi' => 'gray',
                        'raspusten' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('intervencija.naziv')
                    ->label('Intervencija')
                    ->placeholder('— u bazi —')
                    ->limit(40),

                TextColumn::make('bazaPostrojba.naziv')
                    ->label('Bazna postrojba')
                    ->placeholder('—'),

                TextColumn::make('zapovjednik.puno_ime')
                    ->label('Zapovjednik')
                    ->placeholder('—'),

                TextColumn::make('clanstvo_count')
                    ->label('Članova')
                    ->counts([
                        'clanstvo' => fn ($q) => $q->whereNull('izasao_u'),
                    ])
                    ->badge()
                    ->color('info'),

                TextColumn::make('vrijeme_formiranja')
                    ->label('Formiran')
                    ->dateTime('d.m. H:i')
                    ->sortable(),

                TextColumn::make('vrijeme_raspustanja')
                    ->label('Raspušten')
                    ->dateTime('d.m. H:i')
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('trenutni_status')
                    ->label('Status')
                    ->options([
                        'formiran' => 'Formiran',
                        'polazak' => 'Polazak',
                        'na_mjestu' => 'Na mjestu',
                        'intervencija_zavrsena' => 'Završili',
                        'povratak' => 'Povratak',
                        'odmor' => 'Odmor',
                        'cekanje_u_bazi' => 'U bazi',
                        'raspusten' => 'Raspušten',
                    ]),

                SelectFilter::make('baza_postrojba_id')
                    ->label('Bazna postrojba')
                    ->relationship('bazaPostrojba', 'naziv'),
            ])
            ->defaultSort('vrijeme_formiranja', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}