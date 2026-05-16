<?php

namespace App\Filament\Admin\Resources\Intervencijas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class IntervencijasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('broj')
                    ->label('Broj')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('naziv')
                    ->label('Naziv')
                    ->searchable()
                    ->limit(50),

                TextColumn::make('tip_intervencije')
                    ->label('Tip')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pozar' => '🔥 Požar',
                        'poplava' => '🌊 Poplava',
                        'tehnicka' => '🔧 Tehnička',
                        'prometna' => '🚗 Prometna',
                        'spasavanje' => '⛑ Spašavanje',
                        'opasne_tvari' => '☣ Opasne tvari',
                        default => '❓ Ostalo',
                    })
                    ->color(fn ($state) => match($state) {
                        'pozar' => 'danger',
                        'poplava' => 'info',
                        'tehnicka' => 'warning',
                        'prometna' => 'warning',
                        'spasavanje' => 'success',
                        'opasne_tvari' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('prioritet')
                    ->label('Prioritet')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'kriticna' => '🔴 KRITIČNA',
                        'visoka' => '🟡 VISOKA',
                        'standardna' => '🟢 STD',
                        default => $state,
                    })
                    ->color(fn ($state) => match($state) {
                        'kriticna' => 'danger',
                        'visoka' => 'warning',
                        'standardna' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        'aktivna' => 'AKTIVNA',
                        'zatvorena' => 'Zatvorena',
                        'otkazana' => 'Otkazana',
                        default => $state,
                    })
                    ->color(fn ($state) => match($state) {
                        'aktivna' => 'danger',
                        'zatvorena' => 'success',
                        'otkazana' => 'gray',
                        default => 'gray',
                    }),

                TextColumn::make('jls.naziv')
                    ->label('JLS')
                    ->sortable(),

                TextColumn::make('voditelj.name')
                    ->label('Voditelj')
                    ->placeholder('—'),

                TextColumn::make('vrijeme_otvaranja')
                    ->label('Otvorena')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('vrijeme_zatvaranja')
                    ->label('Zatvorena')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'aktivna' => 'Aktivna',
                        'zatvorena' => 'Zatvorena',
                        'otkazana' => 'Otkazana',
                    ])
                    ->default('aktivna'),

                SelectFilter::make('prioritet')
                    ->options([
                        'kriticna' => 'Kritična',
                        'visoka' => 'Visoka',
                        'standardna' => 'Standardna',
                    ]),

                SelectFilter::make('tip_intervencije')
                    ->label('Tip')
                    ->options([
                        'pozar' => 'Požar',
                        'poplava' => 'Poplava',
                        'tehnicka' => 'Tehnička',
                        'prometna' => 'Prometna',
                        'spasavanje' => 'Spašavanje',
                        'opasne_tvari' => 'Opasne tvari',
                        'ostalo' => 'Ostalo',
                    ]),
            ])
            ->defaultSort('vrijeme_otvaranja', 'desc')
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