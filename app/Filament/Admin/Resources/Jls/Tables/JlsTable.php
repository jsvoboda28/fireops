<?php

namespace App\Filament\Admin\Resources\Jls\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class JlsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('naziv')
                    ->label('Naziv')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('tip')
                    ->label('Tip')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'grad' => 'info',
                        'opcina' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'grad' => 'Grad',
                        'opcina' => 'Općina',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('zupanija')
                    ->label('Županija')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('telefon')
                    ->label('Telefon')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                IconColumn::make('aktivan')
                    ->label('Aktivan')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Kreiran')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tip')
                    ->label('Tip')
                    ->options([
                        'grad' => 'Grad',
                        'opcina' => 'Općina',
                    ]),

                TernaryFilter::make('aktivan')
                    ->label('Aktivan')
                    ->boolean()
                    ->trueLabel('Samo aktivni')
                    ->falseLabel('Samo neaktivni')
                    ->placeholder('Svi'),
            ])
            ->defaultSort('naziv', 'asc')
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