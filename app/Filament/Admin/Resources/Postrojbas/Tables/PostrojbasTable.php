<?php

namespace App\Filament\Admin\Resources\Postrojbas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PostrojbasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('skraceni_naziv')
                    ->label('Naziv')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->placeholder(fn ($record) => $record->naziv),

                TextColumn::make('tip')
                    ->label('Tip')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'dvd' => 'success',
                        'jvp' => 'info',
                        'pvpp' => 'warning',
                        'ostalo' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'dvd' => 'DVD',
                        'jvp' => 'JVP',
                        'pvpp' => 'PVPP',
                        'ostalo' => 'Ostalo',
                        default => $state,
                    }),

                TextColumn::make('kategorija')
                    ->label('Kategorija')
                    ->placeholder('-')
                    ->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'I' => 'danger',
                        'II' => 'warning',
                        'III' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('jls.naziv')
                    ->label('JLS')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('mjesto')
                    ->label('Mjesto')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('telefon')
                    ->label('Telefon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('broj_clanova')
                    ->label('Članovi')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('broj_operativnih')
                    ->label('Operativni')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('aktivna')
                    ->label('Aktivna')
                    ->boolean(),

                IconColumn::make('operativno_spremna')
                    ->label('Op. spremna')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('tip')
                    ->label('Tip')
                    ->options([
                        'dvd' => 'DVD',
                        'jvp' => 'JVP',
                        'pvpp' => 'PVPP',
                        'ostalo' => 'Ostalo',
                    ]),

                SelectFilter::make('kategorija')
                    ->label('Kategorija')
                    ->options([
                        'I' => 'I. kategorija',
                        'II' => 'II. kategorija',
                        'III' => 'III. kategorija',
                    ]),

                SelectFilter::make('jls_id')
                    ->label('JLS')
                    ->relationship('jls', 'naziv')
                    ->preload(),

                TernaryFilter::make('aktivna')
                    ->label('Aktivna')
                    ->boolean()
                    ->trueLabel('Samo aktivne')
                    ->falseLabel('Samo neaktivne')
                    ->placeholder('Sve'),

                TernaryFilter::make('operativno_spremna')
                    ->label('Operativno spremna')
                    ->boolean()
                    ->trueLabel('Spremne')
                    ->falseLabel('Nespremne')
                    ->placeholder('Sve'),
            ])
            ->defaultSort('naziv', 'asc')
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