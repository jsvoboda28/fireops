<?php

namespace App\Filament\Admin\Resources\Jls\RelationManagers;

use App\Filament\Admin\Resources\Postrojbas\PostrojbaResource;
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

class PostrojbeRelationManager extends RelationManager
{
    protected static string $relationship = 'postrojbe';

    protected static ?string $title = 'Postrojbe';
    protected static ?string $modelLabel = 'Postrojba';
    protected static ?string $pluralModelLabel = 'Postrojbe';

    public function form(Schema $schema): Schema
    {
        return PostrojbaResource::form($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('naziv')
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
                    ->label('Kat.')
                    ->placeholder('-')
                    ->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'I' => 'danger',
                        'II' => 'warning',
                        'III' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('mjesto')
                    ->label('Mjesto')
                    ->searchable(),

                TextColumn::make('telefon')
                    ->label('Telefon')
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
            ])
            ->defaultSort('naziv', 'asc')
            ->headerActions([
                CreateAction::make()
                    ->label('Nova postrojba'),
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