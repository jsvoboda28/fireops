<?php

namespace App\Filament\Admin\Pages;

use App\Models\Dojava;
use App\Models\Intervencija;
use App\Models\IntervencijaNapomena;
use App\Models\Postrojba;
use App\Models\Tim;
use App\Models\TimClanstvo;
use App\Models\TimRezervacija;
use App\Models\TimStatusLog;
use App\Models\Vatrogasac;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Support\Str;
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

    #[Url(as: 'detalji')]
    public ?string $odabrano = null;

    public ?string $aktivniModal = null;
    public array $modalData = [];

    // ===== NAPOMENA STATE =====
    public string $novaNapomenaTip = 'biljeska';
    public string $novaNapomenaSadrzaj = '';

    public function odaberi(string $tip, int $id): void
    {
        $this->odabrano = "{$tip}:{$id}";
        $this->aktivniModal = null;
        $this->modalData = [];
        $this->novaNapomenaSadrzaj = '';
        $this->novaNapomenaTip = 'biljeska';
    }

    public function zatvoriDetalje(): void
    {
        $this->odabrano = null;
        $this->aktivniModal = null;
        $this->modalData = [];
    }

    public function otvoriModal(string $modal): void
    {
        $this->aktivniModal = $modal;
        $this->modalData = [];
    }

    public function zatvoriModal(): void
    {
        $this->aktivniModal = null;
        $this->modalData = [];
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
        return Intervencija::with([
            'jls', 
            'voditelj', 
            'timovi' => fn($q) => $q->where('trenutni_status', '!=', 'raspusten'),
            'timovi.zapovjednik.postrojba', 
            'timovi.trenutniClanovi.vatrogasac.postrojba',
            'timovi.bazaPostrojba',
            'timovi.aktivneRezervacije.intervencija',
            'dojave',
        ])->find($this->odabranId);
    }

    /**
     * LIVE STREAM — spaja napomene + status logove u jedan kronološki tok.
     * Sortirano od najnovijeg prema najstarijem.
     */
    public function getLiveStreamProperty(): array
    {
        $intervencija = $this->odabranaIntervencija;
        if (!$intervencija) return [];

        $stream = collect();

        // 1. Otvaranje intervencije
        $stream->push([
            'tip' => 'sistem',
            'ikona' => '🔥',
            'naslov' => 'Intervencija otvorena',
            'sadrzaj' => $intervencija->naziv,
            'vrijeme' => $intervencija->vrijeme_otvaranja,
            'autor' => $intervencija->voditelj?->name ?? 'Sistem',
            'boja' => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'border' => '#DC2626'],
        ]);

        // 2. Statusi timova
        $statusLogovi = TimStatusLog::where('intervencija_id', $intervencija->id)
            ->with(['tim', 'autor'])
            ->orderBy('vrijeme', 'desc')
            ->get();
        
        foreach ($statusLogovi as $log) {
            $tipPodaci = match($log->status) {
                'formiran' => ['ikona' => '🆕', 'naslov' => 'Tim formiran', 'boja' => ['bg' => '#FEF3C7', 'text' => '#92400E', 'border' => '#F59E0B']],
                'polazak' => ['ikona' => '🚒', 'naslov' => 'Tim krenuo', 'boja' => ['bg' => '#DBEAFE', 'text' => '#1E40AF', 'border' => '#3B82F6']],
                'na_mjestu' => ['ikona' => '📍', 'naslov' => 'Tim na mjestu', 'boja' => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'border' => '#DC2626']],
                'intervencija_zavrsena' => ['ikona' => '✅', 'naslov' => 'Tim završio', 'boja' => ['bg' => '#D1FAE5', 'text' => '#065F46', 'border' => '#10B981']],
                'povratak' => ['ikona' => '↩️', 'naslov' => 'Tim u povratku', 'boja' => ['bg' => '#E0E7FF', 'text' => '#3730A3', 'border' => '#6366F1']],
                default => ['ikona' => '📌', 'naslov' => ucfirst(str_replace('_', ' ', $log->status)), 'boja' => ['bg' => '#F1F5F9', 'text' => '#475569', 'border' => '#94A3B8']],
            };

            $stream->push([
                'tip' => 'status',
                'ikona' => $tipPodaci['ikona'],
                'naslov' => $tipPodaci['naslov'],
                'sadrzaj' => $log->tim?->naziv ?? '—',
                'napomena' => $log->napomena,
                'vrijeme' => $log->vrijeme,
                'autor' => $log->autor?->name ?? 'Sistem',
                'boja' => $tipPodaci['boja'],
            ]);
        }

        // 3. Napomene
        $napomene = IntervencijaNapomena::where('intervencija_id', $intervencija->id)
            ->with('autor')
            ->orderBy('vrijeme', 'desc')
            ->get();
        
        foreach ($napomene as $n) {
            $stream->push([
                'tip' => 'napomena',
                'ikona' => $n->tip_ikona,
                'naslov' => $n->tip_label,
                'sadrzaj' => $n->sadrzaj,
                'vrijeme' => $n->vrijeme,
                'autor' => $n->autor?->name ?? '—',
                'boja' => $n->tip_boja,
                'napomena_id' => $n->id,
            ]);
        }

        // 4. Dojave vezane
        foreach ($intervencija->dojave as $d) {
            $stream->push([
                'tip' => 'dojava',
                'ikona' => '📞',
                'naslov' => 'Dojava dodana',
                'sadrzaj' => '#' . $d->broj_dojave . ' — ' . $d->adresa,
                'vrijeme' => $d->vrijeme_zaprimanja,
                'autor' => $d->operater?->name ?? '—',
                'boja' => ['bg' => '#F3E8FF', 'text' => '#6D28D9', 'border' => '#8B5CF6'],
            ]);
        }

        // Sortiraj po vremenu, najnoviji prvi
        return $stream
            ->sortByDesc('vrijeme')
            ->values()
            ->toArray();
    }

    public function dodajNapomenu(): void
    {
        $intervencija = $this->odabranaIntervencija;
        if (!$intervencija) return;

        $sadrzaj = trim($this->novaNapomenaSadrzaj);
        if (empty($sadrzaj)) {
            Notification::make()
                ->title('Prazan sadržaj')
                ->body('Upiši nešto prije slanja.')
                ->warning()
                ->send();
            return;
        }

        IntervencijaNapomena::create([
            'intervencija_id' => $intervencija->id,
            'autor_id' => auth()->id(),
            'tip' => $this->novaNapomenaTip,
            'sadrzaj' => $sadrzaj,
            'vrijeme' => now(),
        ]);

        $this->novaNapomenaSadrzaj = '';

        Notification::make()
            ->title('Napomena dodana')
            ->success()
            ->send();

        $this->dispatch('osvjeziMapu');
    }

    public function obrisiNapomenu(int $id): void
    {
        $n = IntervencijaNapomena::find($id);
        if (!$n) return;

        if ($n->autor_id !== auth()->id()) {
            Notification::make()
                ->title('Nije moguće obrisati')
                ->body('Možeš obrisati samo svoje napomene.')
                ->warning()
                ->send();
            return;
        }

        $n->delete();

        Notification::make()
            ->title('Napomena obrisana')
            ->success()
            ->send();
    }

    // ===== AKCIJE TIMOVA =====

    public function timPolazak(int $timId): void
    {
        $this->promijeniStatusTima($timId, 'polazak', 'Tim krenuo na intervenciju');
    }

    public function timNaMjestu(int $timId): void
    {
        $this->promijeniStatusTima($timId, 'na_mjestu', 'Tim stigao na mjesto');
    }

    public function timZavrsili(int $timId): void
    {
        $this->promijeniStatusTima($timId, 'intervencija_zavrsena', 'Tim završio intervenciju');
    }

    public function timPovratak(int $timId): void
    {
        $this->promijeniStatusTima($timId, 'povratak', 'Tim se vraća');
    }

    protected function promijeniStatusTima(int $timId, string $noviStatus, string $opis): void
    {
        $tim = Tim::find($timId);
        if (!$tim) return;

        $stari = $tim->trenutni_status;
        $tim->update(['trenutni_status' => $noviStatus]);

        TimStatusLog::create([
            'tim_id' => $tim->id,
            'status' => $noviStatus,
            'vrijeme' => now(),
            'autor_id' => auth()->id(),
            'intervencija_id' => $tim->intervencija_id,
            'napomena' => $opis,
        ]);

        Notification::make()
            ->title($tim->naziv . ': ' . $stari . ' → ' . $noviStatus)
            ->success()
            ->send();

        $this->dispatch('osvjeziMapu');
    }

    // ===== REZERVACIJE =====

    public function aktivirajRezervaciju(int $rezervacijaId): void
    {
        $rez = TimRezervacija::with('tim')->find($rezervacijaId);
        if (!$rez || !$rez->jeAktivna()) return;

        $tim = $rez->tim;
        if (!$tim) return;

        $tim->update([
            'intervencija_id' => $rez->intervencija_id,
            'trenutni_status' => 'polazak',
        ]);

        TimStatusLog::create([
            'tim_id' => $tim->id,
            'status' => 'polazak',
            'vrijeme' => now(),
            'autor_id' => auth()->id(),
            'intervencija_id' => $rez->intervencija_id,
            'napomena' => 'Aktivirana rezervacija — tim premješten',
        ]);

        $rez->update(['aktivirano_u' => now()]);

        TimRezervacija::where('tim_id', $tim->id)
            ->whereNull('aktivirano_u')
            ->whereNull('otkazano_u')
            ->where('redni_broj', '>', $rez->redni_broj)
            ->decrement('redni_broj');

        Notification::make()
            ->title('Rezervacija aktivirana')
            ->body("Tim '{$tim->naziv}' je premješten na ovu intervenciju.")
            ->success()
            ->send();

        $this->dispatch('osvjeziMapu');
    }

    public function otkaziRezervaciju(int $rezervacijaId): void
    {
        $rez = TimRezervacija::find($rezervacijaId);
        if (!$rez || !$rez->jeAktivna()) return;

        $rez->update([
            'otkazano_u' => now(),
            'razlog_otkazivanja' => 'rucno',
        ]);

        TimRezervacija::where('tim_id', $rez->tim_id)
            ->whereNull('aktivirano_u')
            ->whereNull('otkazano_u')
            ->where('redni_broj', '>', $rez->redni_broj)
            ->decrement('redni_broj');

        Notification::make()
            ->title('Rezervacija otkazana')
            ->success()
            ->send();

        $this->dispatch('osvjeziMapu');
    }

    // ===== KREIRAJ NOVI TIM =====

    public function kreirajTim(): void
    {
        $intervencija = $this->odabranaIntervencija;
        if (!$intervencija) return;

        $podaci = $this->modalData;
        
        if (empty($podaci['naziv']) || empty($podaci['baza_postrojba_id']) || empty($podaci['zapovjednik_id'])) {
            Notification::make()
                ->title('Nedostaju podaci')
                ->body('Naziv, bazna postrojba i zapovjednik su obavezni.')
                ->warning()
                ->send();
            return;
        }

        $tim = Tim::create([
            'naziv' => $podaci['naziv'],
            'intervencija_id' => $intervencija->id,
            'baza_postrojba_id' => $podaci['baza_postrojba_id'],
            'zapovjednik_id' => $podaci['zapovjednik_id'],
            'trenutni_status' => 'formiran',
            'zadatak' => $podaci['zadatak'] ?? null,
            'vrijeme_formiranja' => now(),
        ]);

        TimClanstvo::create([
            'tim_id' => $tim->id,
            'vatrogasac_id' => $podaci['zapovjednik_id'],
            'uloga' => 'zapovjednik',
            'usao_u' => now(),
        ]);

        TimStatusLog::create([
            'tim_id' => $tim->id,
            'status' => 'formiran',
            'vrijeme' => now(),
            'autor_id' => auth()->id(),
            'intervencija_id' => $intervencija->id,
        ]);

        Notification::make()
            ->title('Tim formiran')
            ->body("Tim '{$tim->naziv}' je kreiran.")
            ->success()
            ->send();

        $this->zatvoriModal();
        $this->dispatch('osvjeziMapu');
    }

    // ===== POŠALJI POSTOJEĆI TIM =====

    public function posaljiTim(): void
    {
        $intervencija = $this->odabranaIntervencija;
        if (!$intervencija) return;

        $timId = $this->modalData['tim_id'] ?? null;
        if (!$timId) return;

        $tim = Tim::find($timId);
        if (!$tim) return;

        $staraIntervencija = $tim->intervencija_id;
        $opis = $staraIntervencija 
            ? 'Tim premješten s druge intervencije'
            : 'Tim poslan iz baze na intervenciju';

        $tim->update([
            'intervencija_id' => $intervencija->id,
            'trenutni_status' => 'polazak',
        ]);

        TimStatusLog::create([
            'tim_id' => $tim->id,
            'status' => 'polazak',
            'vrijeme' => now(),
            'autor_id' => auth()->id(),
            'intervencija_id' => $intervencija->id,
            'napomena' => $opis,
        ]);

        Notification::make()
            ->title('Tim poslan')
            ->body("Tim '{$tim->naziv}' je sada na ovoj intervenciji.")
            ->success()
            ->send();

        $this->zatvoriModal();
        $this->dispatch('osvjeziMapu');
    }

    // ===== REZERVIRAJ TIM =====

    public function rezervirajTim(): void
    {
        $intervencija = $this->odabranaIntervencija;
        if (!$intervencija) return;

        $timId = $this->modalData['tim_id'] ?? null;
        if (!$timId) return;

        $tim = Tim::find($timId);
        if (!$tim) return;

        $vec = TimRezervacija::where('tim_id', $tim->id)
            ->where('intervencija_id', $intervencija->id)
            ->whereNull('aktivirano_u')
            ->whereNull('otkazano_u')
            ->exists();
        
        if ($vec) {
            Notification::make()
                ->title('Već rezervirano')
                ->body("Tim '{$tim->naziv}' već ima aktivnu rezervaciju.")
                ->warning()
                ->send();
            return;
        }

        $sljedeci = TimRezervacija::where('tim_id', $tim->id)
            ->whereNull('aktivirano_u')
            ->whereNull('otkazano_u')
            ->max('redni_broj') ?? 0;
        
        TimRezervacija::create([
            'tim_id' => $tim->id,
            'intervencija_id' => $intervencija->id,
            'redni_broj' => $sljedeci + 1,
            'rezervirao_id' => auth()->id(),
            'rezervirano_u' => now(),
            'napomena' => $this->modalData['napomena'] ?? null,
        ]);

        Notification::make()
            ->title('Tim rezerviran')
            ->body("Tim '{$tim->naziv}' je u redu čekanja.")
            ->success()
            ->send();

        $this->zatvoriModal();
        $this->dispatch('osvjeziMapu');
    }

    // ===== DODAJ DOJAVU U INTERVENCIJU =====

    public function dodajDojavuUIntervenciju(): void
    {
        $intervencija = $this->odabranaIntervencija;
        if (!$intervencija) return;

        $dojavaId = $this->modalData['dojava_id'] ?? null;
        if (!$dojavaId) return;

        $dojava = Dojava::find($dojavaId);
        if (!$dojava) return;

        $dojava->update([
            'intervencija_id' => $intervencija->id,
            'status' => 'dodijeljena',
        ]);

        Notification::make()
            ->title('Dojava dodana')
            ->body("Dojava #{$dojava->broj_dojave} je sad dio intervencije.")
            ->success()
            ->send();

        $this->zatvoriModal();
        $this->dispatch('osvjeziMapu');
    }

    // ===== ZATVORI INTERVENCIJU =====

    public function zatvoriIntervenciju(): void
    {
        $intervencija = $this->odabranaIntervencija;
        if (!$intervencija) return;

        $intervencija->update([
            'status' => 'zatvorena',
            'vrijeme_zatvaranja' => now(),
        ]);

        TimRezervacija::where('intervencija_id', $intervencija->id)
            ->whereNull('aktivirano_u')
            ->whereNull('otkazano_u')
            ->update([
                'otkazano_u' => now(),
                'razlog_otkazivanja' => 'intervencija_zatvorena',
            ]);

        Notification::make()
            ->title('Intervencija zatvorena')
            ->body('Aktivne rezervacije su otkazane.')
            ->success()
            ->send();

        $this->zatvoriDetalje();
        $this->dispatch('osvjeziMapu');
    }

    // ===== AKCIJE DOJAVE =====

    public function otvoriIntervencijuIzDojave(): void
    {
        $dojava = $this->odabranaDojava;
        if (!$dojava) return;

        if ($dojava->intervencija_id) {
            $this->odaberi('intervencija', $dojava->intervencija_id);
            return;
        }

        $tipMap = [
            'pozar' => 'pozar',
            'olujno_nevrijeme' => 'tehnicka',
            'poplava' => 'poplava',
            'snijeg_led' => 'tehnicka',
            'klizište' => 'tehnicka',
            'tuca' => 'tehnicka',
            'potres' => 'spasavanje',
            'spasavanje' => 'spasavanje',
            'opasne_tvari' => 'opasne_tvari',
        ];

        $brojIntervencija = Intervencija::whereYear('created_at', now()->year)->count() + 1;
        $brojString = now()->year . '-INT-' . str_pad((string) $brojIntervencija, 6, '0', STR_PAD_LEFT);

        $intervencija = Intervencija::create([
            'broj' => $brojString,
            'naziv' => $this->predloziNaziv($dojava),
            'pocetna_dojava_id' => $dojava->id,
            'jls_id' => $dojava->jls_id,
            'adresa' => $dojava->adresa,
            'latitude' => $dojava->latitude,
            'longitude' => $dojava->longitude,
            'tip_intervencije' => $tipMap[$dojava->tip_nepogode] ?? 'tehnicka',
            'prioritet' => $dojava->prioritet,
            'status' => 'aktivna',
            'voditelj_id' => auth()->id(),
            'vrijeme_otvaranja' => now(),
            'opis' => $dojava->opis,
        ]);

        $dojava->update([
            'intervencija_id' => $intervencija->id,
            'status' => 'dodijeljena',
        ]);

        Notification::make()
            ->title('Intervencija otvorena')
            ->body("Intervencija #{$intervencija->broj} kreirana iz dojave.")
            ->success()
            ->send();

        $this->odaberi('intervencija', $intervencija->id);
        $this->dispatch('osvjeziMapu');
    }

    protected function predloziNaziv(Dojava $dojava): string
    {
        $tipNaziv = match($dojava->tip_nepogode) {
            'pozar' => 'Požar',
            'olujno_nevrijeme' => 'Olujno nevrijeme',
            'poplava' => 'Poplava',
            'snijeg_led' => 'Snijeg/led',
            'klizište' => 'Klizište',
            'tuca' => 'Tuča',
            'potres' => 'Potres',
            'spasavanje' => 'Spašavanje',
            'opasne_tvari' => 'Opasne tvari',
            default => 'Intervencija',
        };
        return $tipNaziv . ' — ' . Str::limit($dojava->adresa, 50);
    }

    // ===== PODACI ZA MODAL OPCIJE =====

    public function getPostrojbeOpcijeProperty(): array
    {
        return Postrojba::where('aktivna', true)
            ->orderBy('naziv')
            ->pluck('naziv', 'id')
            ->toArray();
    }

    public function getVatrogasciOpcijeProperty(): array
    {
        return Vatrogasac::where('status', 'aktivan')
            ->where('operativan', true)
            ->with('postrojba')
            ->orderBy('prezime')
            ->get()
            ->mapWithKeys(fn ($v) => [
                $v->id => $v->prezime . ' ' . $v->ime . ' (' . ($v->postrojba?->naziv ?? '?') . ')'
            ])
            ->toArray();
    }

    public function getTimoviZaSlanjeProperty(): array
    {
        $intervencijaId = $this->odabranaIntervencija?->id;
        if (!$intervencijaId) return [];

        return Tim::where('trenutni_status', '!=', 'raspusten')
            ->where(function ($q) use ($intervencijaId) {
                $q->whereNull('intervencija_id')
                  ->orWhere('intervencija_id', '!=', $intervencijaId);
            })
            ->with(['bazaPostrojba', 'trenutniClanovi', 'intervencija'])
            ->orderBy('naziv')
            ->get()
            ->mapWithKeys(function ($t) {
                $brojClanova = $t->trenutniClanovi->count();
                $gdje = $t->intervencija_id 
                    ? '🔥 ' . Str::limit($t->intervencija?->naziv ?? '?', 25)
                    : '🏠 u bazi';
                return [
                    $t->id => $t->naziv 
                        . ' (' . ($t->bazaPostrojba?->skraceni_naziv ?? $t->bazaPostrojba?->naziv ?? '?') . ')'
                        . ' • ' . $brojClanova . ' članova'
                        . ' • ' . $gdje
                ];
            })
            ->toArray();
    }

    public function getSlobodneDojaveOpcijeProperty(): array
    {
        return Dojava::whereNull('intervencija_id')
            ->orderBy('vrijeme_zaprimanja', 'desc')
            ->limit(50)
            ->get()
            ->mapWithKeys(function ($d) {
                $prio = match($d->prioritet) {
                    'kriticna' => '🔴',
                    'visoka' => '🟡',
                    default => '🟢',
                };
                return [
                    $d->id => $prio . ' #' . $d->broj_dojave 
                        . ' • ' . Str::limit($d->adresa, 40)
                ];
            })
            ->toArray();
    }

    // ===== GLAVNI DATA =====

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
            'liveStream' => $this->liveStream,
        ];
    }
}