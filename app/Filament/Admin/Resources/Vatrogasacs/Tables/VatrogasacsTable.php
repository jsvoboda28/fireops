<?php

namespace App\Filament\Admin\Resources\Vatrogasacs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class VatrogasacsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('prezime')
                    ->label('Prezime')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('ime')
                    ->label('Ime')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('postrojba.skraceni_naziv')
                    ->label('Postrojba')
                    ->searchable()
                    ->sortable()
                    ->placeholder(fn ($record) => $record->postrojba?->naziv ?? '-'),

                TextColumn::make('kategorija')
                    ->label('Kategorija')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'visi_casnik' => 'danger',
                        'casnik', 'casnik_I' => 'warning',
                        'docasnik', 'docasnik_I' => 'info',
                        default => 'gray',
                    })
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

                TextColumn::make('mobitel')
                    ->label('Mobitel')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'aktivan' => 'success',
                        'mirovanje' => 'warning',
                        'neaktivan' => 'gray',
                        'suspenzija' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'aktivan' => 'Aktivan',
                        'mirovanje' => 'Mirovanje',
                        'neaktivan' => 'Neaktivan',
                        'suspenzija' => 'Suspenzija',
                        default => $state,
                    }),

                IconColumn::make('operativan')
                    ->label('Operativan')
                    ->boolean(),

                IconColumn::make('pin_hash')
                    ->label('PIN')
                    ->boolean()
                    ->trueIcon('heroicon-o-key')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->getStateUsing(fn ($record) => filled($record->pin_hash))
                    ->toggleable(),

                TextColumn::make('datum_pristupa')
                    ->label('Pristupio')
                    ->date('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('postrojba_id')
                    ->label('Postrojba')
                    ->relationship('postrojba', 'naziv')
                    ->preload()
                    ->searchable(),

                SelectFilter::make('kategorija')
                    ->label('Kategorija')
                    ->options([
                        'vatrogasac' => 'Vatrogasac',
                        'vatrogasac_I' => 'Vatrogasac I.',
                        'vatrogasac_II' => 'Vatrogasac II.',
                        'docasnik' => 'Dočasnik',
                        'docasnik_I' => 'Dočasnik I.',
                        'casnik' => 'Časnik',
                        'casnik_I' => 'Časnik I.',
                        'visi_casnik' => 'Viši časnik',
                    ]),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'aktivan' => 'Aktivan',
                        'mirovanje' => 'Mirovanje',
                        'neaktivan' => 'Neaktivan',
                        'suspenzija' => 'Suspenzija',
                    ]),

                TernaryFilter::make('operativan')
                    ->label('Operativan')
                    ->boolean()
                    ->trueLabel('Samo operativni')
                    ->falseLabel('Samo neoperativni')
                    ->placeholder('Svi'),
            ])
            ->defaultSort('prezime', 'asc')
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