<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Dojava;
use App\Models\OperativniDogadjaj;
use App\Models\Postrojba;
use App\Models\Vatrogasac;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatistikaSistema extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Aktivni događaji
        $aktivniDogadjaji = OperativniDogadjaj::where('status', 'aktivan')->count();
        $pracenjeDogadjaji = OperativniDogadjaj::where('status', 'pracenje')->count();

        // Dojave danas
        $dojaveDanas = Dojava::whereDate('vrijeme_zaprimanja', today())->count();
        $dojaveOtvoreneDanas = Dojava::whereDate('vrijeme_zaprimanja', today())
            ->whereIn('status', ['zaprimljena', 'dodijeljena', 'u_tijeku'])
            ->count();

        // Postrojbe
        $ukupnoPostrojbi = Postrojba::where('aktivna', true)->count();
        $operativnoSpremnih = Postrojba::where('aktivna', true)
            ->where('operativno_spremna', true)
            ->count();

        // Vatrogasci
        $ukupnoVatrogasaca = Vatrogasac::where('status', 'aktivan')->count();
        $operativnihVatrogasaca = Vatrogasac::where('status', 'aktivan')
            ->where('operativan', true)
            ->count();

        return [
            Stat::make('Aktivni događaji', $aktivniDogadjaji)
                ->description($pracenjeDogadjaji > 0 
                    ? $pracenjeDogadjaji . ' u praćenju' 
                    : 'Bez događaja u praćenju')
                ->descriptionIcon($aktivniDogadjaji > 0 ? 'heroicon-o-fire' : 'heroicon-o-check-circle')
                ->color($aktivniDogadjaji > 0 ? 'danger' : 'success'),

            Stat::make('Dojave danas', $dojaveDanas)
                ->description($dojaveOtvoreneDanas . ' otvorenih')
                ->descriptionIcon('heroicon-o-bell-alert')
                ->color($dojaveOtvoreneDanas > 0 ? 'warning' : 'gray'),

            Stat::make('Postrojbe', $ukupnoPostrojbi)
                ->description($operativnoSpremnih . ' operativno spremnih')
                ->descriptionIcon('heroicon-o-shield-check')
                ->color('success'),

            Stat::make('Vatrogasci', $ukupnoVatrogasaca)
                ->description($operativnihVatrogasaca . ' operativnih')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info'),
        ];
    }
}