<?php

namespace App\Filament\Admin\Resources\OperativniDogadjajs\RelationManagers;

use App\Filament\Admin\Resources\Dojavas\DojavaResource;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DojaveRelationManager extends RelationManager
{
    protected static string $relationship = 'dojave';

    protected static ?string $title = 'Dojave';
    protected static ?string $modelLabel = 'Dojava';
    protected static ?string $pluralModelLabel = 'Dojave';

    public function form(Schema $schema): Schema
    {
        return DojavaResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('broj_dojave')
            ->columns([
                TextColumn::make('broj_dojave')
                    ->label('Broj')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('vrijeme_zaprimanja')
                    ->label('Zaprimljeno')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('adresa')
                    ->label('Lokacija')
                    ->searchable()
                    ->limit(40),

                TextColumn::make('jls.naziv')
                    ->label('JLS')
                    ->searchable(),

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
            ])
            ->defaultSort('vrijeme_zaprimanja', 'desc')
            ->headerActions([
                CreateAction::make()
                    ->label('Nova dojava'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}