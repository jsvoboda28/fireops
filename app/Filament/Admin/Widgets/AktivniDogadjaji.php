<?php

namespace App\Filament\Admin\Widgets;

use App\Models\OperativniDogadjaj;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AktivniDogadjaji extends BaseWidget
{
    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Aktivni operativni događaji';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OperativniDogadjaj::query()
                    ->whereIn('status', ['aktivan', 'pracenje'])
                    ->withCount('dojave')
                    ->latest('vrijeme_otvaranja')
            )
            ->columns([
                TextColumn::make('naziv')
                    ->label('Naziv')
                    ->searchable()
                    ->weight('bold')
                    ->limit(50),

                TextColumn::make('jls.naziv')
                    ->label('JLS')
                    ->placeholder('Županijska')
                    ->sortable(),

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

                TextColumn::make('razina')
                    ->label('Razina')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'lokalna' => 'info',
                        'zupanijska' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'lokalna' => 'Lokalna',
                        'zupanijska' => 'Županijska',
                        default => $state,
                    }),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'pracenje' => 'info',
                        'aktivan' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'pracenje' => 'Praćenje',
                        'aktivan' => 'Aktivan',
                        default => $state,
                    }),

                TextColumn::make('stupanj_sukoba')
                    ->label('Stupanj')
                    ->placeholder('-')
                    ->badge()
                    ->color(fn (?string $state) => match ($state) {
                        'I' => 'success',
                        'II' => 'info',
                        'III' => 'warning',
                        'IV' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('vrijeme_otvaranja')
                    ->label('Otvoren')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('trajanje')
                    ->label('Traje')
                    ->state(fn ($record) => $record->trajanje)
                    ->badge()
                    ->color('gray'),

                TextColumn::make('dojave_count')
                    ->label('Dojave')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('voditelj.name')
                    ->label('Voditelj')
                    ->placeholder('-')
                    ->toggleable(),
            ])
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('Nema aktivnih događaja')
            ->emptyStateDescription('Trenutno nema otvorenih operativnih događaja.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}