<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Dojava;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class AktivneDojave extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Aktivne dojave';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Dojava::query()
                    ->whereIn('status', ['zaprimljena', 'dodijeljena', 'u_tijeku'])
                    ->latest('vrijeme_zaprimanja')
            )
            ->columns([
                TextColumn::make('broj_dojave')
                    ->label('Broj')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('vrijeme_zaprimanja')
                    ->label('Zaprimljeno')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('adresa')
                    ->label('Lokacija')
                    ->limit(40)
                    ->searchable(),

                TextColumn::make('jls.naziv')
                    ->label('JLS')
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
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'zaprimljena' => 'Zaprimljena',
                        'dodijeljena' => 'Dodijeljena',
                        'u_tijeku' => 'U tijeku',
                        default => $state,
                    }),
            ])
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->emptyStateHeading('Nema aktivnih dojava')
            ->emptyStateDescription('Sve dojave su završene ili nema unesenih dojava.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}