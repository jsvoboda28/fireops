<?php

namespace App\Filament\Admin\Resources\OperativniDogadjajs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OperativniDogadjajsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('naziv')
                    ->label('Naziv')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(50),

                TextColumn::make('jls.naziv')
                    ->label('JLS')
                    ->placeholder('Županijska razina')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tip_nepogode')
                    ->label('Tip')
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'olujno_nevrijeme' => 'Olujno',
                        'poplava' => 'Poplava',
                        'pozar' => 'Požar',
                        'snijeg_led' => 'Snijeg/led',
                        'klizište' => 'Klizište',
                        'tuca' => 'Tuča',
                        'potres' => 'Potres',
                        'ostalo' => 'Ostalo',
                        default => $state,
                    }),

                TextColumn::make('razina')
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

                TextColumn::make('status')
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

                TextColumn::make('stupanj_sukoba')
                    ->label('Stupanj')
                    ->placeholder('-')
                    ->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'I' => 'success',
                        'II' => 'info',
                        'III' => 'warning',
                        'IV' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('vrijeme_otvaranja')
                    ->label('Otvoren')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('vrijeme_zatvaranja')
                    ->label('Zatvoren')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('Aktivan')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('voditelj.name')
                    ->label('Voditelj')
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('dojave_count')
                    ->label('Dojave')
                    ->counts('dojave')
                    ->badge()
                    ->color('gray'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pracenje' => 'Praćenje',
                        'aktivan' => 'Aktivan',
                        'zatvoren' => 'Zatvoren',
                    ]),

                SelectFilter::make('razina')
                    ->label('Razina')
                    ->options([
                        'lokalna' => 'Lokalna',
                        'zupanijska' => 'Županijska',
                    ]),

                SelectFilter::make('jls_id')
                    ->label('JLS')
                    ->relationship('jls', 'naziv')
                    ->preload(),

                SelectFilter::make('tip_nepogode')
                    ->label('Tip nepogode')
                    ->options([
                        'olujno_nevrijeme' => 'Olujno nevrijeme',
                        'poplava' => 'Poplava',
                        'pozar' => 'Požar',
                        'snijeg_led' => 'Snijeg / led',
                        'klizište' => 'Klizište',
                        'tuca' => 'Tuča',
                        'potres' => 'Potres',
                        'ostalo' => 'Ostalo',
                    ]),
            ])
            ->defaultSort('vrijeme_otvaranja', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}