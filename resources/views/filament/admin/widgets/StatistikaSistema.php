<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Dojava;
use App\Models\OperativniDogadjaj;
use App\Models\Postrojba;
use App\Models\Vatrogasac;
use Filament\Widgets\Widget;

class StatistikaSistema extends Widget
{
    protected string $view = 'filament.admin.widgets.statistika-sistema';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function getViewData(): array
    {
        return [
            'kartice' => [
                [
                    'naslov' => 'Aktivni događaji',
                    'broj' => OperativniDogadjaj::whereIn('status', ['aktivan', 'pracenje'])->count(),
                    'podnaslov' => OperativniDogadjaj::where('status', 'aktivan')->count() . ' aktivnih, ' 
                        . OperativniDogadjaj::where('status', 'pracenje')->count() . ' u praćenju',
                    'icon' => 'fire',
                    'boja' => OperativniDogadjaj::whereIn('status', ['aktivan', 'pracenje'])->count() > 0 ? 'red' : 'gray',
                ],
                [
                    'naslov' => 'Dojave danas',
                    'broj' => Dojava::whereDate('vrijeme_zaprimanja', today())->count(),
                    'podnaslov' => Dojava::whereDate('vrijeme_zaprimanja', today())
                        ->whereIn('status', ['zaprimljena', 'dodijeljena', 'u_tijeku'])->count() . ' otvorenih',
                    'icon' => 'bell',
                    'boja' => 'orange',
                ],
                [
                    'naslov' => 'Postrojbe',
                    'broj' => Postrojba::where('aktivna', true)->count(),
                    'podnaslov' => Postrojba::where('aktivna', true)
                        ->where('operativno_spremna', true)->count() . ' operativno spremnih',
                    'icon' => 'shield',
                    'boja' => 'emerald',
                ],
                [
                    'naslov' => 'Vatrogasci',
                    'broj' => Vatrogasac::where('status', 'aktivan')->count(),
                    'podnaslov' => Vatrogasac::where('status', 'aktivan')
                        ->where('operativan', true)->count() . ' operativnih',
                    'icon' => 'users',
                    'boja' => 'blue',
                ],
            ],
        ];
    }
}