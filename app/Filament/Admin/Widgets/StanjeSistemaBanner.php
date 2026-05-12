<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Dojava;
use App\Models\OperativniDogadjaj;
use Filament\Widgets\Widget;

class StanjeSistemaBanner extends Widget
{
    protected string $view = 'filament.admin.widgets.stanje-sistema-banner';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -1;

    public function getViewData(): array
    {
        $aktivnihDogadjaja = OperativniDogadjaj::where('status', 'aktivan')->count();
        $pracenjeDogadjaja = OperativniDogadjaj::where('status', 'pracenje')->count();
        $kritickihDojava = Dojava::where('prioritet', 'kriticna')
            ->whereIn('status', ['zaprimljena', 'dodijeljena', 'u_tijeku'])
            ->count();

        if ($kritickihDojava > 0 || $aktivnihDogadjaja >= 3) {
            $naslov = 'KRITIČNO STANJE';
            $opis = $kritickihDojava . ' kritičnih dojava, ' . $aktivnihDogadjaja . ' aktivnih događaja';
            $boja = 'red';
        } elseif ($aktivnihDogadjaja > 0) {
            $naslov = 'AKTIVNO STANJE';
            $opis = $aktivnihDogadjaja . ' aktivnih operativnih događaja';
            $boja = 'orange';
        } elseif ($pracenjeDogadjaja > 0) {
            $naslov = 'STANJE PRAĆENJA';
            $opis = $pracenjeDogadjaja . ' događaja u praćenju';
            $boja = 'yellow';
        } else {
            $naslov = 'SUSTAV U PRIPRAVNOSTI';
            $opis = 'Nema aktivnih događaja — redovno stanje';
            $boja = 'emerald';
        }

        return [
            'naslov' => $naslov,
            'opis' => $opis,
            'boja' => $boja,
            'aktivnihDogadjaja' => $aktivnihDogadjaja,
            'pracenjeDogadjaja' => $pracenjeDogadjaja,
            'kritickihDojava' => $kritickihDojava,
            'datum' => now()->translatedFormat('l, d.m.Y'),
        ];
    }
}