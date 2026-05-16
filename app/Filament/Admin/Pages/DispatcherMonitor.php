<?php

namespace App\Filament\Admin\Pages;

use App\Models\Dojava;
use App\Models\Intervencija;
use App\Models\Tim;
use App\Models\TimStatusLog;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Livewire\Attributes\Url;

class DispatcherMonitor extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';
    
    protected static ?string $navigationLabel = 'Dispečerski centar';
    
    protected static ?string $title = 'Dispečerski centar';
    
    protected static ?int $navigationSort = -10;

    protected string $view = 'filament.admin.pages.dispatcher-monitor';

    protected static ?string $slug = 'dispatcher';

    public Width|string|null $maxContentWidth = Width::Full;

    protected ?string $pollingInterval = '30s';

    /**
     * URL parametar koji drži trenutno odabrani detalj
     * Format: "dojava:5" ili "intervencija:3" ili null
     */
    #[Url(as: 'detalji')]
    public ?string $odabrano = null;

    public function odaberi(string $tip, int $id): void
    {
        $this->odabrano = "{$tip}:{$id}";
    }

    public function zatvoriDetalje(): void
    {
        $this->odabrano = null;
    }

    public function getOdabranTipProperty(): ?string
    {
        if (!$this->odabrano) return null;
        return explode(':', $this->odabrano)[0] ?? null;
    }

    public function getOdabranIdProperty(): ?int
    {
        if (!$this->odabrano) return null;
        $parts = explode(':', $this->odabrano);
        return isset($parts[1]) ? (int) $parts[1] : null;
    }

    public function getOdabranaDojavaProperty(): ?Dojava
    {
        if ($this->odabranTip !== 'dojava' || !$this->odabranId) return null;
        return Dojava::with(['jls', 'intervencija', 'operater'])
            ->find($this->odabranId);
    }

    public function getOdabranaIntervencijaProperty(): ?Intervencija
    {
        if ($this->odabranTip !== 'intervencija' || !$this->odabranId) return null;
        return Intervencija::with(['jls', 'voditelj', 'timovi.zapovjednik', 'timovi.trenutniClanovi', 'dojave'])
            ->find($this->odabranId);
    }

    public function getViewData(): array
    {
        $dojave = Dojava::query()
            ->whereIn('status', ['zaprimljena', 'dodijeljena', 'u_tijeku'])
            ->with(['jls', 'intervencija'])
            ->orderByRaw("CASE prioritet 
                WHEN 'kriticna' THEN 1 
                WHEN 'visoka' THEN 2 
                WHEN 'standardna' THEN 3 
                ELSE 4 
            END")
            ->latest('vrijeme_zaprimanja')
            ->get();

        $intervencije = Intervencija::query()
            ->where('status', 'aktivna')
            ->with(['jls', 'voditelj', 'timovi.zapovjednik', 'timovi.trenutniClanovi'])
            ->orderByRaw("CASE prioritet 
                WHEN 'kriticna' THEN 1 
                WHEN 'visoka' THEN 2 
                ELSE 3 
            END")
            ->latest('vrijeme_otvaranja')
            ->get();

        $timeline = TimStatusLog::query()
            ->with(['tim', 'intervencija', 'autor'])
            ->orderBy('vrijeme', 'desc')
            ->limit(50)
            ->get();

        $brojKritickih = $dojave->where('prioritet', 'kriticna')->count();
        $brojAktivnihIntervencija = $intervencije->count();

        if ($brojKritickih > 0 || $brojAktivnihIntervencija >= 5) {
            $stanje = ['naslov' => 'KRITIČNO', 'boja' => '#DC2626'];
        } elseif ($brojAktivnihIntervencija > 0) {
            $stanje = ['naslov' => 'AKTIVNO', 'boja' => '#F97316'];
        } else {
            $stanje = ['naslov' => 'PRIPRAVNOST', 'boja' => '#059669'];
        }

        return [
            'dojave' => $dojave,
            'intervencije' => $intervencije,
            'timeline' => $timeline,
            'stanje' => $stanje,
            'brojDojava' => $dojave->count(),
            'brojIntervencija' => $intervencije->count(),
            'brojTimova' => Tim::where('trenutni_status', '!=', 'raspusten')->count(),
            'odabranaDojava' => $this->odabranaDojava,
            'odabranaIntervencija' => $this->odabranaIntervencija,
        ];
    }
}