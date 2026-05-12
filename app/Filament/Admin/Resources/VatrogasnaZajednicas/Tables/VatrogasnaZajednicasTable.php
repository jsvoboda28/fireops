<?php

namespace App\Filament\Admin\Resources\VatrogasnaZajednicas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class VatrogasnaZajednicasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('skraceni_naziv')
                    ->label('Skraćeni naziv')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->placeholder(fn ($record) => $record->naziv),

                TextColumn::make('tip')
                    ->label('Tip')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'zupanijska' => 'danger',
                        'podrucna' => 'warning',
                        'opcinska' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'zupanijska' => 'Županijska',
                        'podrucna' => 'Područna',
                        'opcinska' => 'Općinska',
                        default => $state,
                    }),

                TextColumn::make('roditelj.skraceni_naziv')
                    ->label('Pripada')
                    ->placeholder('-'),

                TextColumn::make('jlsovi_count')
                    ->label('JLS')
                    ->counts('jlsovi')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('korisnici_count')
                    ->label('Korisnici')
                    ->counts('korisnici')
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                TextColumn::make('telefon')
                    ->label('Telefon')
                    ->toggleable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->toggleable(),

                IconColumn::make('aktivna')
                    ->label('Aktivna')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('tip')
                    ->label('Tip')
                    ->options([
                        'zupanijska' => 'Županijska',
                        'podrucna' => 'Područna',
                        'opcinska' => 'Općinska',
                    ]),

                TernaryFilter::make('aktivna')
                    ->label('Aktivna')
                    ->boolean()
                    ->placeholder('Sve'),
            ])
            ->defaultSort('tip', 'asc')
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