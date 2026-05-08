<?php

namespace App\Filament\Admin\Resources\Vozilos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class VozilosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('registracija')
                    ->label('Registracija')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('marka')
                    ->label('Marka')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('model')
                    ->label('Model')
                    ->searchable()
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('godina_proizvodnje')
                    ->label('Godina')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('tip')
                    ->label('Tip')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'navalno' => 'danger',
                        'autocisterna' => 'info',
                        'sumsko' => 'success',
                        'tehnicko' => 'warning',
                        'terensko' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'navalno' => 'Navalno',
                        'autocisterna' => 'Autocisterna',
                        'sumsko' => 'Šumsko',
                        'tehnicko' => 'Tehničko',
                        'terensko' => 'Terensko 4x4',
                        'ljestve' => 'Ljestve',
                        'kombi' => 'Kombi',
                        'kamion' => 'Kamion',
                        'ostalo' => 'Ostalo',
                        default => $state,
                    }),

                TextColumn::make('postrojba.skraceni_naziv')
                    ->label('Postrojba')
                    ->searchable()
                    ->sortable()
                    ->placeholder(fn ($record) => $record->postrojba?->naziv ?? '-'),

                TextColumn::make('kapacitet_vode')
                    ->label('Voda (L)')
                    ->numeric()
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'operativno' => 'success',
                        'na_servisu' => 'warning',
                        'u_kvaru' => 'danger',
                        'povučeno' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'operativno' => 'Operativno',
                        'na_servisu' => 'Na servisu',
                        'u_kvaru' => 'U kvaru',
                        'povučeno' => 'Povučeno',
                        default => $state,
                    }),

                IconColumn::make('aktivno')
                    ->label('Aktivno')
                    ->boolean(),

                TextColumn::make('tehnicki_pregled_do')
                    ->label('Tehnički do')
                    ->date('d.m.Y')
                    ->sortable()
                    ->color(fn ($state) => match (true) {
                        !$state => 'gray',
                        $state->isPast() => 'danger',
                        $state->diffInDays(now()) <= 30 => 'warning',
                        default => null,
                    })
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('registrirano_do')
                    ->label('Reg. do')
                    ->date('d.m.Y')
                    ->sortable()
                    ->color(fn ($state) => match (true) {
                        !$state => 'gray',
                        $state->isPast() => 'danger',
                        $state->diffInDays(now()) <= 30 => 'warning',
                        default => null,
                    })
                    ->placeholder('-')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('tip')
                    ->label('Tip vozila')
                    ->options([
                        'navalno' => 'Navalno',
                        'autocisterna' => 'Autocisterna',
                        'sumsko' => 'Šumsko',
                        'tehnicko' => 'Tehničko',
                        'terensko' => 'Terensko 4x4',
                        'ljestve' => 'Ljestve',
                        'kombi' => 'Kombi',
                        'kamion' => 'Kamion',
                        'ostalo' => 'Ostalo',
                    ]),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'operativno' => 'Operativno',
                        'na_servisu' => 'Na servisu',
                        'u_kvaru' => 'U kvaru',
                        'povučeno' => 'Povučeno',
                    ]),

                SelectFilter::make('postrojba_id')
                    ->label('Postrojba')
                    ->relationship('postrojba', 'naziv')
                    ->preload()
                    ->searchable(),

                TernaryFilter::make('aktivno')
                    ->label('Aktivno')
                    ->boolean()
                    ->trueLabel('Samo aktivna')
                    ->falseLabel('Samo neaktivna')
                    ->placeholder('Sva'),
            ])
            ->defaultSort('registracija', 'asc')
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