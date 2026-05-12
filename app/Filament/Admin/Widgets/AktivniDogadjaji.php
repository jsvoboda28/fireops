<?php

namespace App\Filament\Admin\Widgets;

use App\Models\OperativniDogadjaj;
use Filament\Widgets\Widget;

class AktivniDogadjaji extends Widget
{
    protected string $view = 'filament.admin.widgets.aktivni-dogadjaji';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 0;

    public function getViewData(): array
    {
        $dogadjaji = OperativniDogadjaj::query()
            ->whereIn('status', ['aktivan', 'pracenje'])
            ->withCount('dojave')
            ->with(['jls', 'voditelj'])
            ->orderByRaw("CASE status WHEN 'aktivan' THEN 1 WHEN 'pracenje' THEN 2 ELSE 3 END")
            ->orderByRaw("CASE stupanj_sukoba WHEN 'IV' THEN 1 WHEN 'III' THEN 2 WHEN 'II' THEN 3 WHEN 'I' THEN 4 ELSE 5 END")
            ->latest('vrijeme_otvaranja')
            ->limit(6)
            ->get();

        return [
            'dogadjaji' => $dogadjaji,
            'ukupno' => OperativniDogadjaj::whereIn('status', ['aktivan', 'pracenje'])->count(),
        ];
    }
}