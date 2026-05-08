<?php

namespace App\Filament\Admin\Resources\Dojavas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DojavasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('broj_dojave')
                    ->label('Broj')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('vrijeme_zaprimanja')
                    ->label('Zaprimljeno')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('adresa')
                    ->label('Lokacija')
                    ->searchable()
                    ->limit(40),

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

                TextColumn::make('prioritet')
                    ->label('Prioritet')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'kriticna' => 'danger',
                        'visoka' => 'warning',
                        'standardna' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'kriticna' => 'Kritična',
                        'visoka' => 'Visoka',
                        'standardna' => 'Standardna',
                        default => $state,
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'zaprimljena' => 'gray',
                        'dodijeljena' => 'info',
                        'u_tijeku' => 'warning',
                        'zavrsena' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'zaprimljena' => 'Zaprimljena',
                        'dodijeljena' => 'Dodijeljena',
                        'u_tijeku' => 'U tijeku',
                        'zavrsena' => 'Završena',
                        default => $state,
                    }),
            ])
            ->filters([
                SelectFilter::make('prioritet')
                    ->label('Prioritet')
                    ->options([
                        'kriticna' => 'Kritična',
                        'visoka' => 'Visoka',
                        'standardna' => 'Standardna',
                    ]),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'zaprimljena' => 'Zaprimljena',
                        'dodijeljena' => 'Dodijeljena',
                        'u_tijeku' => 'U tijeku',
                        'zavrsena' => 'Završena',
                    ]),

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
            ->defaultSort('vrijeme_zaprimanja', 'desc')
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