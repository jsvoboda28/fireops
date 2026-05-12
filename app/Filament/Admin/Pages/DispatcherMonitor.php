<?php

namespace App\Filament\Admin\Pages;

use App\Models\Dojava;
use App\Models\OperativniDogadjaj;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;

class DispatcherMonitor extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';
    
    protected static ?string $navigationLabel = 'Dispečerski monitor';
    
    protected static ?string $title = 'Dispečerski monitor';
    
    protected static ?int $navigationSort = -10;

    protected string $view = 'filament.admin.pages.dispatcher-monitor';

    protected static ?string $slug = 'dispatcher';

    public Width|string|null $maxContentWidth = Width::Full;

    public function getViewData(): array
    {
        $dojave = Dojava::query()
            ->whereIn('status', ['zaprimljena', 'dodijeljena', 'u_tijeku'])
            ->with(['jls', 'dogadjaj'])
            ->orderByRaw("CASE prioritet 
                WHEN 'kriticna' THEN 1 
                WHEN 'visoka' THEN 2 
                WHEN 'standardna' THEN 3 
                ELSE 4 
            END")
            ->latest('vrijeme_zaprimanja')
            ->get();

        $dogadjaji = OperativniDogadjaj::query()
            ->whereIn('status', ['aktivan', 'pracenje'])
            ->withCount('dojave')
            ->with(['jls', 'voditelj'])
            ->orderByRaw("CASE status WHEN 'aktivan' THEN 1 WHEN 'pracenje' THEN 2 ELSE 3 END")
            ->latest('vrijeme_otvaranja')
            ->get();

        $aktivnihDogadjaja = OperativniDogadjaj::where('status', 'aktivan')->count();
        $kritickihDojava = Dojava::where('prioritet', 'kriticna')
            ->whereIn('status', ['zaprimljena', 'dodijeljena', 'u_tijeku'])
            ->count();

        if ($kritickihDojava > 0 || $aktivnihDogadjaja >= 3) {
            $stanje = ['naslov' => 'KRITIČNO STANJE', 'boja' => '#DC2626'];
        } elseif ($aktivnihDogadjaja > 0) {
            $stanje = ['naslov' => 'AKTIVNO STANJE', 'boja' => '#F97316'];
        } else {
            $stanje = ['naslov' => 'PRIPRAVNOST', 'boja' => '#059669'];
        }

        return [
            'dojave' => $dojave,
            'dogadjaji' => $dogadjaji,
            'stanje' => $stanje,
            'brojDojava' => $dojave->count(),
            'brojDogadjaja' => $dogadjaji->count(),
        ];
    }
}