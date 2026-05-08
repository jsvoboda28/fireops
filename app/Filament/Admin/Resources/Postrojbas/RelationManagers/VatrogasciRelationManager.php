<?php

namespace App\Filament\Admin\Resources\Postrojbas\RelationManagers;

use App\Filament\Admin\Resources\Vatrogasacs\VatrogasacResource;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VatrogasciRelationManager extends RelationManager
{
    protected static string $relationship = 'vatrogasci';

    protected static ?string $title = 'Vatrogasci';
    protected static ?string $modelLabel = 'Vatrogasac';
    protected static ?string $pluralModelLabel = 'Vatrogasci';

    public function form(Schema $schema): Schema
    {
        return VatrogasacResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('prezime')
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
            ])
            ->filters([
                SelectFilter::make('kategorija')
                    ->label('Kategorija')
                    ->options([
                        'vatrogasac' => 'Vatrogasac',
                        'docasnik' => 'Dočasnik',
                        'casnik' => 'Časnik',
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
            ])
            ->defaultSort('prezime', 'asc')
            ->headerActions([
                CreateAction::make()
                    ->label('Novi vatrogasac'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}