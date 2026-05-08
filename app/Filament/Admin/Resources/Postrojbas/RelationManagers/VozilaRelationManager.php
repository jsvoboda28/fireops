<?php

namespace App\Filament\Admin\Resources\Postrojbas\RelationManagers;

use App\Filament\Admin\Resources\Vozilos\VoziloResource;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VozilaRelationManager extends RelationManager
{
    protected static string $relationship = 'vozila';

    protected static ?string $title = 'Vozila';
    protected static ?string $modelLabel = 'Vozilo';
    protected static ?string $pluralModelLabel = 'Vozila';

    public function form(Schema $schema): Schema
    {
        return VoziloResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('registracija')
            ->columns([
                TextColumn::make('registracija')
                    ->label('Registracija')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('marka')
                    ->label('Marka')
                    ->searchable(),

                TextColumn::make('model')
                    ->label('Model')
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

                TextColumn::make('godina_proizvodnje')
                    ->label('Godina')
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('kapacitet_vode')
                    ->label('Voda (L)')
                    ->numeric()
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
                    ->label('Tip')
                    ->options([
                        'navalno' => 'Navalno',
                        'autocisterna' => 'Autocisterna',
                        'sumsko' => 'Šumsko',
                        'tehnicko' => 'Tehničko',
                        'terensko' => 'Terensko',
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
            ])
            ->defaultSort('registracija', 'asc')
            ->headerActions([
                CreateAction::make()
                    ->label('Novo vozilo'),
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