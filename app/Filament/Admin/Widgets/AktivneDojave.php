<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Dojava;
use Filament\Widgets\Widget;

class AktivneDojave extends Widget
{
    protected string $view = 'filament.admin.widgets.aktivne-dojave';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;

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
            ->limit(8)
            ->get();

        return [
            'dojave' => $dojave,
            'ukupno' => Dojava::whereIn('status', ['zaprimljena', 'dodijeljena', 'u_tijeku'])->count(),
        ];
    }
}