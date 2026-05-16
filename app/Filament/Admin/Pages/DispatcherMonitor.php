<?php

namespace App\Filament\Admin\Pages;

use App\Models\Dojava;
use App\Models\Intervencija;
use App\Models\IntervencijaNapomena;
use App\Models\KucniBroj;
use App\Models\Naselje;
use App\Models\Postrojba;
use App\Models\Tim;
use App\Models\TimClanstvo;
use App\Models\TimRezervacija;
use App\Models\TimStatusLog;
use App\Models\TimVozilo;
use App\Models\Ulica;
use App\Models\Vatrogasac;
use App\Models\Vozilo;
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

    #[Url(as: 'tab')]
    public string $aktivniTab = 'sve';

    #[Url(as: 'prio')]
    public string $aktivniPrio = 'sve';

    #[Url(as: 'q')]
    public string $pretraga = '';

    public ?string $aktivniModal = null;
    public array $modalData = [];

    public ?int $upravljaniTimId = null;
    public ?int $upravljanjeNoviClanId = null;
    public string $upravljanjeNovaUloga = 'clan';
    public string $upravljanjeNoviZadatak = '';
    public ?int $upravljanjeNovoVoziloId = null;

    public string $novaNapomenaTip = 'biljeska';
    public string $novaNapomenaSadrzaj = '';

    public ?int $urediDojavaId = null;
    public string $novaDojavaTip = 'pozar';
    public string $novaDojavaPrioritet = 'standardna';
    public string $novaDojavaOpis = '';
    public string $novaDojavaStatus = 'zaprimljena';
    
    public string $novaDojavaNaseljePretraga = '';
    public ?int $novaDojavaNaseljeId = null;
    public string $novaDojavaNaseljeNaziv = '';
    
    public string $novaDojavaUlicaPretraga = '';
    public ?int $novaDojavaUlicaId = null;
    public string $novaDojavaUlicaNaziv = '';
    public bool $novaDojavaBezUlice = false;
    
    public string $novaDojavaKucniBroj = '';
    public ?int $novaDojavaKucniBrojId = null;
    public bool $novaDojavaBezKucnogBroja = false;
    
    public ?float $novaDojavaLatitude = null;
    public ?float $novaDojavaLongitude = null;

    public function odaberi(string $tip, int $id): void
    {
        $this->odabrano = "{$tip}:{$id}";
        $this->aktivniModal = null;
        $this->modalData = [];
        $this->upravljaniTimId = null;
        $this->novaNapomenaSadrzaj = '';
        $this->novaNapomenaTip = 'biljeska';
    }

    public function zatvoriDetalje(): void
    {
        $this->odabrano = null;
        $this->aktivniModal = null;
        $this->modalData = [];
        $this->upravljaniTimId = null;
    }

    public function postaviTab(string $tab): void
    {
        $this->aktivniTab = $tab;
    }

    public function postaviPrio(string $prio): void
    {
        $this->aktivniPrio = $prio;
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
        $this->upravljaniTimId = null;
        $this->upravljanjeNoviClanId = null;
        $this->upravljanjeNovaUloga = 'clan';
        $this->upravljanjeNoviZadatak = '';
        $this->upravljanjeNovoVoziloId = null;
        $this->resetirajNovuDojavu();
    }

    public function otvoriUpravljanjeTimom(int $timId): void
    {
        $this->upravljaniTimId = $timId;
        $this->aktivniModal = 'upravljajTimom';
        $this->modalData = [];
        $this->upravljanjeNoviClanId = null;
        $this->upravljanjeNovaUloga = 'clan';
        $this->upravljanjeNovoVoziloId = null;
        $tim = Tim::find($timId);
        $this->upravljanjeNoviZadatak = $tim?->zadatak ?? '';
    }

    public function getUpravljaniTimProperty(): ?Tim
    {
        if (!$this->upravljaniTimId) return null;
        return Tim::with([
            'bazaPostrojba', 
            'zapovjednik', 
            'trenutniClanovi.vatrogasac.postrojba',
            'trenutnaVozila.vozilo.postrojba',
        ])->find($this->upravljaniTimId);
    }

    public function dodajClanaUTim(): void
    {
        $tim = $this->upravljaniTim;
        if (!$tim) return;

        if (!$this->upravljanjeNoviClanId) {
            Notification::make()->title('Odaberi vatrogasca')->warning()->send();
            return;
        }

        $vec = TimClanstvo::where('tim_id', $tim->id)
            ->where('vatrogasac_id', $this->upravljanjeNoviClanId)
            ->whereNull('izasao_u')
            ->exists();
        
        if ($vec) {
            Notification::make()->title('Već u timu')->warning()->send();
            return;
        }

        TimClanstvo::create([
            'tim_id' => $tim->id,
            'vatrogasac_id' => $this->upravljanjeNoviClanId,
            'uloga' => $this->upravljanjeNovaUloga,
            'usao_u' => now(),
        ]);

        $this->upravljanjeNoviClanId = null;
        $this->upravljanjeNovaUloga = 'clan';

        Notification::make()->title('Član dodan')->success()->send();
        $this->dispatch('osvjeziMapu');
    }

    public function ukloniClanaIzTima(int $clanstvoId): void
    {
        $c = TimClanstvo::find($clanstvoId);
        if (!$c) return;

        $c->update(['izasao_u' => now()]);

        Notification::make()->title('Član uklonjen')->success()->send();
        $this->dispatch('osvjeziMapu');
    }

    public function azurirajZadatakTima(): void
    {
        $tim = $this->upravljaniTim;
        if (!$tim) return;

        $tim->update(['zadatak' => $this->upravljanjeNoviZadatak ?: null]);

        Notification::make()->title('Zadatak ažuriran')->success()->send();
    }

    public function dodajVoziloUTim(): void
    {
        $tim = $this->upravljaniTim;
        if (!$tim) return;

        if (!$this->upravljanjeNovoVoziloId) {
            Notification::make()->title('Odaberi vozilo')->warning()->send();
            return;
        }

        $vec = TimVozilo::where('tim_id', $tim->id)
            ->where('vozilo_id', $this->upravljanjeNovoVoziloId)
            ->whereNull('skinuto_u')
            ->exists();
        
        if ($vec) {
            Notification::make()->title('Vozilo je već u timu')->warning()->send();
            return;
        }

        // Provjeri je li vozilo već dodijeljeno drugom timu
        $drugiTim = TimVozilo::where('vozilo_id', $this->upravljanjeNovoVoziloId)
            ->where('tim_id', '!=', $tim->id)
            ->whereNull('skinuto_u')
            ->with('tim')
            ->first();
        
        if ($drugiTim) {
            Notification::make()
                ->title('Vozilo nije dostupno')
                ->body("Vozilo je trenutno u timu '{$drugiTim->tim?->naziv}'.")
                ->warning()
                ->send();
            return;
        }

        TimVozilo::create([
            'tim_id' => $tim->id,
            'vozilo_id' => $this->upravljanjeNovoVoziloId,
            'dodano_u' => now(),
        ]);

        $this->upravljanjeNovoVoziloId = null;

        Notification::make()->title('Vozilo dodano u tim')->success()->send();
        $this->dispatch('osvjeziMapu');
    }

    public function ukloniVoziloIzTima(int $timVoziloId): void
    {
        $tv = TimVozilo::find($timVoziloId);
        if (!$tv) return;

        $tv->update(['skinuto_u' => now()]);

        Notification::make()->title('Vozilo uklonjeno iz tima')->success()->send();
        $this->dispatch('osvjeziMapu');
    }

    public function getVozilaOpcijeProperty(): array
    {
        $tim = $this->upravljaniTim;
        $bazaPostrojbaId = $tim?->baza_postrojba_id;

        // Vozila iz iste baze prvo, onda ostala
        $vozila = Vozilo::where('aktivno', true)
            ->where('status', 'operativno')
            ->with('postrojba')
            ->orderByRaw($bazaPostrojbaId ? "(postrojba_id = ?) DESC" : "id ASC", $bazaPostrojbaId ? [$bazaPostrojbaId] : [])
            ->orderBy('registracija')
            ->get();

        // Filter — izbaci vozila koja su već u drugim timovima (osim ovog)
        $zauzeti = TimVozilo::whereNull('skinuto_u')
            ->when($tim, fn($q) => $q->where('tim_id', '!=', $tim->id))
            ->pluck('vozilo_id')
            ->toArray();

        return $vozila
            ->filter(fn($v) => !in_array($v->id, $zauzeti))
            ->mapWithKeys(function ($v) use ($bazaPostrojbaId) {
                $marker = $v->postrojba_id == $bazaPostrojbaId ? '⭐ ' : '';
                $tipKratko = strtoupper($v->tip ?? '?');
                return [
                    $v->id => $marker . $v->registracija 
                        . ' • ' . $tipKratko 
                        . ' • ' . $v->marka . ($v->model ? ' ' . $v->model : '')
                        . ' (' . ($v->postrojba?->skraceni_naziv ?? $v->postrojba?->naziv ?? '?') . ')'
                ];
            })
            ->toArray();
    }

    // ===== NOVA DOJAVA =====

    public function otvoriNovuDojavu(): void
    {
        $this->aktivniModal = 'novaDojava';
        $this->urediDojavaId = null;
        $this->resetirajNovuDojavu();
    }

    public function otvoriUrediDojavu(int $dojavaId): void
    {
        $dojava = Dojava::find($dojavaId);
        if (!$dojava) return;

        $this->urediDojavaId = $dojava->id;
        $this->aktivniModal = 'urediDojavu';
        
        $this->novaDojavaTip = $dojava->tip_nepogode ?? 'pozar';
        $this->novaDojavaPrioritet = $dojava->prioritet ?? 'standardna';
        $this->novaDojavaOpis = $dojava->opis ?? '';
        $this->novaDojavaStatus = $dojava->status ?? 'zaprimljena';
        
        $this->novaDojavaNaseljePretraga = '';
        $this->novaDojavaNaseljeId = null;
        $this->novaDojavaNaseljeNaziv = '';
        $this->novaDojavaUlicaPretraga = '';
        $this->novaDojavaUlicaId = null;
        $this->novaDojavaUlicaNaziv = '';
        $this->novaDojavaBezUlice = false;
        $this->novaDojavaKucniBroj = '';
        $this->novaDojavaKucniBrojId = null;
        $this->novaDojavaBezKucnogBroja = false;
        
        $this->novaDojavaLatitude = $dojava->latitude ? (float) $dojava->latitude : null;
        $this->novaDojavaLongitude = $dojava->longitude ? (float) $dojava->longitude : null;
        
        $kolone = \Schema::getColumnListing('dojavas');
        
        if (in_array('naselje_id', $kolone) && $dojava->naselje_id) {
            $naselje = Naselje::find($dojava->naselje_id);
            if ($naselje) {
                $this->novaDojavaNaseljeId = $naselje->id;
                $this->novaDojavaNaseljeNaziv = $naselje->naziv;
            }
        }
        
        if (in_array('ulica_id', $kolone) && $dojava->ulica_id) {
            $ulica = Ulica::find($dojava->ulica_id);
            if ($ulica) {
                $this->novaDojavaUlicaId = $ulica->id;
                $this->novaDojavaUlicaNaziv = $ulica->naziv;
            }
        }
        
        if (in_array('kucni_broj_id', $kolone) && $dojava->kucni_broj_id) {
            $kb = KucniBroj::find($dojava->kucni_broj_id);
            if ($kb) {
                $this->novaDojavaKucniBrojId = $kb->id;
                $this->novaDojavaKucniBroj = $kb->broj;
            }
        }
    }

    public function resetirajNovuDojavu(): void
    {
        $this->urediDojavaId = null;
        $this->novaDojavaTip = 'pozar';
        $this->novaDojavaPrioritet = 'standardna';
        $this->novaDojavaOpis = '';
        $this->novaDojavaStatus = 'zaprimljena';
        $this->novaDojavaNaseljePretraga = '';
        $this->novaDojavaNaseljeId = null;
        $this->novaDojavaNaseljeNaziv = '';
        $this->novaDojavaUlicaPretraga = '';
        $this->novaDojavaUlicaId = null;
        $this->novaDojavaUlicaNaziv = '';
        $this->novaDojavaBezUlice = false;
        $this->novaDojavaKucniBroj = '';
        $this->novaDojavaKucniBrojId = null;
        $this->novaDojavaBezKucnogBroja = false;
        $this->novaDojavaLatitude = null;
        $this->novaDojavaLongitude = null;
    }

    public function odaberiNaseljeUDojavi(int $naseljeId): void
    {
        $naselje = Naselje::find($naseljeId);
        if (!$naselje) return;
        
        $this->novaDojavaNaseljeId = $naselje->id;
        $this->novaDojavaNaseljeNaziv = $naselje->naziv;
        $this->novaDojavaNaseljePretraga = '';
        
        $this->novaDojavaUlicaId = null;
        $this->novaDojavaUlicaNaziv = '';
        $this->novaDojavaUlicaPretraga = '';
        $this->novaDojavaBezUlice = false;
        $this->novaDojavaKucniBroj = '';
        $this->novaDojavaKucniBrojId = null;
        $this->novaDojavaBezKucnogBroja = false;
        $this->novaDojavaLatitude = null;
        $this->novaDojavaLongitude = null;
    }

    public function poniStavkuNaselje(): void
    {
        $this->novaDojavaNaseljeId = null;
        $this->novaDojavaNaseljeNaziv = '';
        $this->novaDojavaNaseljePretraga = '';
        $this->novaDojavaUlicaId = null;
        $this->novaDojavaUlicaNaziv = '';
        $this->novaDojavaUlicaPretraga = '';
        $this->novaDojavaBezUlice = false;
        $this->novaDojavaKucniBroj = '';
        $this->novaDojavaKucniBrojId = null;
        $this->novaDojavaBezKucnogBroja = false;
        $this->novaDojavaLatitude = null;
        $this->novaDojavaLongitude = null;
    }

    public function odaberiUlicuUDojavi(int $ulicaId): void
    {
        $ulica = Ulica::find($ulicaId);
        if (!$ulica) return;
        
        $this->novaDojavaUlicaId = $ulica->id;
        $this->novaDojavaUlicaNaziv = $ulica->naziv;
        $this->novaDojavaUlicaPretraga = '';
        $this->novaDojavaBezUlice = false;
        
        $prviKB = KucniBroj::where('ulica_id', $ulicaId)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->first();
        
        if ($prviKB) {
            $this->novaDojavaLatitude = (float) $prviKB->latitude;
            $this->novaDojavaLongitude = (float) $prviKB->longitude;
        }
        
        $this->novaDojavaKucniBroj = '';
        $this->novaDojavaKucniBrojId = null;
        $this->novaDojavaBezKucnogBroja = false;
    }

    public function poniStavkuUlica(): void
    {
        $this->novaDojavaUlicaId = null;
        $this->novaDojavaUlicaNaziv = '';
        $this->novaDojavaUlicaPretraga = '';
        $this->novaDojavaBezUlice = false;
        $this->novaDojavaKucniBroj = '';
        $this->novaDojavaKucniBrojId = null;
        $this->novaDojavaBezKucnogBroja = false;
    }

    public function postaviBezUlice(): void
    {
        $this->novaDojavaBezUlice = true;
        $this->novaDojavaUlicaId = null;
        $this->novaDojavaUlicaNaziv = '';
        $this->novaDojavaUlicaPretraga = '';
        $this->novaDojavaKucniBroj = '';
        $this->novaDojavaKucniBrojId = null;
        $this->novaDojavaBezKucnogBroja = true;
    }

    public function odaberiKucniBrojUDojavi(int $kbId): void
    {
        $kb = KucniBroj::find($kbId);
        if (!$kb) return;
        
        $this->novaDojavaKucniBrojId = $kb->id;
        $this->novaDojavaKucniBroj = $kb->broj;
        $this->novaDojavaBezKucnogBroja = false;
        
        if ($kb->latitude && $kb->longitude) {
            $this->novaDojavaLatitude = (float) $kb->latitude;
            $this->novaDojavaLongitude = (float) $kb->longitude;
        }
    }

    public function postaviBezKucnogBroja(): void
    {
        $this->novaDojavaBezKucnogBroja = true;
        $this->novaDojavaKucniBrojId = null;
        $this->novaDojavaKucniBroj = '';
    }

    public function poniStavkuKucniBroj(): void
    {
        $this->novaDojavaKucniBrojId = null;
        $this->novaDojavaKucniBroj = '';
        $this->novaDojavaBezKucnogBroja = false;
    }

    public function postaviKoordinate(float $lat, float $lng): void
    {
        $this->novaDojavaLatitude = $lat;
        $this->novaDojavaLongitude = $lng;
    }

    public function kreirajNovuDojavu(): void
    {
        if (!$this->novaDojavaNaseljeId) {
            Notification::make()->title('Nedostaje naselje')->body('Odaberi naselje.')->warning()->send();
            return;
        }

        if (!$this->novaDojavaUlicaId && !$this->novaDojavaBezUlice) {
            Notification::make()->title('Nedostaje ulica')->body('Odaberi ulicu ili označi "Bez ulice".')->warning()->send();
            return;
        }

        $naselje = Naselje::find($this->novaDojavaNaseljeId);
        if (!$naselje) {
            Notification::make()->title('Naselje nije pronađeno')->warning()->send();
            return;
        }

        $adresa = '';
        if ($this->novaDojavaUlicaNaziv) {
            $adresa .= $this->novaDojavaUlicaNaziv;
            if ($this->novaDojavaKucniBroj) {
                $adresa .= ' ' . $this->novaDojavaKucniBroj;
            }
            $adresa .= ', ';
        }
        $adresa .= $this->novaDojavaNaseljeNaziv;

        $godina = now()->year;
        $brojOvogodisnji = Dojava::whereYear('created_at', $godina)->count() + 1;
        $brojDojave = $godina . '-DOJ-' . str_pad((string) $brojOvogodisnji, 6, '0', STR_PAD_LEFT);

        $dojavaPodaci = [
            'broj_dojave' => $brojDojave,
            'tip_nepogode' => $this->novaDojavaTip,
            'adresa' => $adresa,
            'jls_id' => $naselje->jls_id,
            'prioritet' => $this->novaDojavaPrioritet,
            'opis' => trim($this->novaDojavaOpis) ?: null,
            'status' => 'zaprimljena',
            'operater_id' => auth()->id(),
            'vrijeme_zaprimanja' => now(),
        ];

        if ($this->novaDojavaLatitude && $this->novaDojavaLongitude) {
            $dojavaPodaci['latitude'] = $this->novaDojavaLatitude;
            $dojavaPodaci['longitude'] = $this->novaDojavaLongitude;
        }

        $kolone = \Schema::getColumnListing('dojavas');
        if (in_array('naselje_id', $kolone)) {
            $dojavaPodaci['naselje_id'] = $this->novaDojavaNaseljeId;
        }
        if (in_array('ulica_id', $kolone)) {
            $dojavaPodaci['ulica_id'] = $this->novaDojavaUlicaId;
        }
        if (in_array('kucni_broj_id', $kolone)) {
            $dojavaPodaci['kucni_broj_id'] = $this->novaDojavaKucniBrojId;
        }

        $dojava = Dojava::create($dojavaPodaci);

        Notification::make()
            ->title('Dojava zaprimljena')
            ->body("Dojava #{$dojava->broj_dojave} je kreirana.")
            ->success()
            ->send();

        $this->zatvoriModal();
        $this->odaberi('dojava', $dojava->id);
        $this->dispatch('osvjeziMapu');
    }

    public function azurirajDojavu(): void
    {
        if (!$this->urediDojavaId) return;
        
        $dojava = Dojava::find($this->urediDojavaId);
        if (!$dojava) return;

        if (!$this->novaDojavaNaseljeId) {
            Notification::make()->title('Nedostaje naselje')->body('Odaberi naselje.')->warning()->send();
            return;
        }

        if (!$this->novaDojavaUlicaId && !$this->novaDojavaBezUlice) {
            Notification::make()->title('Nedostaje ulica')->body('Odaberi ulicu ili označi "Bez ulice".')->warning()->send();
            return;
        }

        $naselje = Naselje::find($this->novaDojavaNaseljeId);
        if (!$naselje) return;

        $adresa = '';
        if ($this->novaDojavaUlicaNaziv) {
            $adresa .= $this->novaDojavaUlicaNaziv;
            if ($this->novaDojavaKucniBroj) {
                $adresa .= ' ' . $this->novaDojavaKucniBroj;
            }
            $adresa .= ', ';
        }
        $adresa .= $this->novaDojavaNaseljeNaziv;

        $podaci = [
            'tip_nepogode' => $this->novaDojavaTip,
            'adresa' => $adresa,
            'jls_id' => $naselje->jls_id,
            'prioritet' => $this->novaDojavaPrioritet,
            'opis' => trim($this->novaDojavaOpis) ?: null,
            'status' => $this->novaDojavaStatus,
            'latitude' => $this->novaDojavaLatitude,
            'longitude' => $this->novaDojavaLongitude,
        ];

        $kolone = \Schema::getColumnListing('dojavas');
        if (in_array('naselje_id', $kolone)) {
            $podaci['naselje_id'] = $this->novaDojavaNaseljeId;
        }
        if (in_array('ulica_id', $kolone)) {
            $podaci['ulica_id'] = $this->novaDojavaUlicaId;
        }
        if (in_array('kucni_broj_id', $kolone)) {
            $podaci['kucni_broj_id'] = $this->novaDojavaKucniBrojId;
        }

        $dojava->update($podaci);

        Notification::make()
            ->title('Dojava ažurirana')
            ->body("Dojava #{$dojava->broj_dojave} je spremljena.")
            ->success()
            ->send();

        $this->zatvoriModal();
        $this->dispatch('osvjeziMapu');
    }

    public function brzaPromjenaStatusaDojave(string $noviStatus): void
    {
        $dojava = $this->odabranaDojava;
        if (!$dojava) return;

        $dojava->update(['status' => $noviStatus]);

        Notification::make()
            ->title('Status dojave promijenjen')
            ->body($dojava->broj_dojave . ' → ' . $noviStatus)
            ->success()
            ->send();

        $this->dispatch('osvjeziMapu');
    }

    public function brzaPromjenaPrioritetaDojave(string $noviPrio): void
    {
        $dojava = $this->odabranaDojava;
        if (!$dojava) return;

        $dojava->update(['prioritet' => $noviPrio]);

        Notification::make()
            ->title('Prioritet promijenjen')
            ->body($dojava->broj_dojave . ' → ' . $noviPrio)
            ->success()
            ->send();

        $this->dispatch('osvjeziMapu');
    }

    public function getNaseljaRezultatiProperty(): array
    {
        $q = trim($this->novaDojavaNaseljePretraga);
        if (strlen($q) < 2) return [];
        
        return Naselje::with('jls')
            ->where('naziv', 'ilike', $q . '%')
            ->orderBy('naziv')
            ->limit(15)
            ->get()
            ->map(fn($n) => [
                'id' => $n->id,
                'naziv' => $n->naziv,
                'jls' => $n->jls?->naziv ?? '—',
                'postanski_broj' => $n->postanski_broj,
            ])
            ->toArray();
    }

    public function getUliceRezultatiProperty(): array
    {
        if (!$this->novaDojavaNaseljeId) return [];
        
        $q = trim($this->novaDojavaUlicaPretraga);
        
        $query = Ulica::where('naselje_id', $this->novaDojavaNaseljeId);
        
        if (strlen($q) >= 1) {
            $query->where('naziv', 'ilike', '%' . $q . '%');
        }
        
        return $query->orderBy('naziv')
            ->limit(30)
            ->pluck('naziv', 'id')
            ->toArray();
    }

    public function getKucniBrojeviRezultatiProperty(): array
    {
        if (!$this->novaDojavaUlicaId) return [];
        
        return KucniBroj::where('ulica_id', $this->novaDojavaUlicaId)
            ->orderByRaw("CAST(regexp_replace(broj, '[^0-9]', '', 'g') AS INTEGER) NULLS LAST")
            ->orderBy('broj')
            ->get()
            ->map(fn($kb) => [
                'id' => $kb->id,
                'broj' => $kb->broj,
                'lat' => (float) $kb->latitude,
                'lng' => (float) $kb->longitude,
            ])
            ->toArray();
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

    public function getLiveStreamProperty(): array
    {
        $intervencija = $this->odabranaIntervencija;
        if (!$intervencija) return [];

        $stream = collect();

        $stream->push([
            'tip' => 'sistem',
            'ikona' => '🔥',
            'naslov' => 'Intervencija otvorena',
            'sadrzaj' => $intervencija->naziv,
            'vrijeme' => $intervencija->vrijeme_otvaranja,
            'autor' => $intervencija->voditelj?->name ?? 'Sistem',
            'boja' => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'border' => '#DC2626'],
        ]);

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
            Notification::make()->title('Prazan sadržaj')->body('Upiši nešto prije slanja.')->warning()->send();
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

        Notification::make()->title('Napomena dodana')->success()->send();
        $this->dispatch('osvjeziMapu');
    }

    public function obrisiNapomenu(int $id): void
    {
        $n = IntervencijaNapomena::find($id);
        if (!$n) return;

        if ($n->autor_id !== auth()->id()) {
            Notification::make()->title('Nije moguće obrisati')->body('Možeš obrisati samo svoje napomene.')->warning()->send();
            return;
        }

        $n->delete();

        Notification::make()->title('Napomena obrisana')->success()->send();
    }

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

        Notification::make()->title($tim->naziv . ': ' . $stari . ' → ' . $noviStatus)->success()->send();
        $this->dispatch('osvjeziMapu');
    }

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

        Notification::make()->title('Rezervacija aktivirana')->body("Tim '{$tim->naziv}' je premješten.")->success()->send();
        $this->dispatch('osvjeziMapu');
    }

    public function otkaziRezervaciju(int $rezervacijaId): void
    {
        $rez = TimRezervacija::find($rezervacijaId);
        if (!$rez || !$rez->jeAktivna()) return;

        $rez->update(['otkazano_u' => now(), 'razlog_otkazivanja' => 'rucno']);

        TimRezervacija::where('tim_id', $rez->tim_id)
            ->whereNull('aktivirano_u')
            ->whereNull('otkazano_u')
            ->where('redni_broj', '>', $rez->redni_broj)
            ->decrement('redni_broj');

        Notification::make()->title('Rezervacija otkazana')->success()->send();
        $this->dispatch('osvjeziMapu');
    }

    public function kreirajTim(): void
    {
        $intervencija = $this->odabranaIntervencija;
        if (!$intervencija) return;

        $podaci = $this->modalData;
        
        if (empty($podaci['naziv']) || empty($podaci['baza_postrojba_id']) || empty($podaci['zapovjednik_id'])) {
            Notification::make()->title('Nedostaju podaci')->body('Naziv, bazna postrojba i zapovjednik su obavezni.')->warning()->send();
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

        Notification::make()->title('Tim formiran')->body("Tim '{$tim->naziv}' je kreiran.")->success()->send();
        $this->zatvoriModal();
        $this->dispatch('osvjeziMapu');
    }

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

        Notification::make()->title('Tim poslan')->body("Tim '{$tim->naziv}' je sada na ovoj intervenciji.")->success()->send();
        $this->zatvoriModal();
        $this->dispatch('osvjeziMapu');
    }

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
            Notification::make()->title('Već rezervirano')->body("Tim '{$tim->naziv}' već ima aktivnu rezervaciju.")->warning()->send();
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

        Notification::make()->title('Tim rezerviran')->body("Tim '{$tim->naziv}' je u redu čekanja.")->success()->send();
        $this->zatvoriModal();
        $this->dispatch('osvjeziMapu');
    }

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

        Notification::make()->title('Dojava dodana')->body("Dojava #{$dojava->broj_dojave} je sad dio intervencije.")->success()->send();
        $this->zatvoriModal();
        $this->dispatch('osvjeziMapu');
    }

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

        Notification::make()->title('Intervencija zatvorena')->body('Aktivne rezervacije su otkazane.')->success()->send();
        $this->zatvoriDetalje();
        $this->dispatch('osvjeziMapu');
    }

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

        Notification::make()->title('Intervencija otvorena')->body("Intervencija #{$intervencija->broj} kreirana.")->success()->send();
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

    public function getViewData(): array
    {
        $dojaveQuery = Dojava::query()
            ->whereIn('status', ['zaprimljena', 'dodijeljena', 'u_tijeku'])
            ->with(['jls', 'intervencija']);
        
        $intervencijeQuery = Intervencija::query()
            ->where('status', 'aktivna')
            ->with(['jls', 'voditelj', 'timovi.zapovjednik', 'timovi.trenutniClanovi']);

        if ($this->aktivniPrio !== 'sve') {
            $dojaveQuery->where('prioritet', $this->aktivniPrio);
            $intervencijeQuery->where('prioritet', $this->aktivniPrio);
        }

        if (!empty(trim($this->pretraga))) {
            $q = '%' . trim($this->pretraga) . '%';
            $dojaveQuery->where(function($qb) use ($q) {
                $qb->where('adresa', 'ilike', $q)
                   ->orWhere('broj_dojave', 'ilike', $q);
            });
            $intervencijeQuery->where(function($qb) use ($q) {
                $qb->where('naziv', 'ilike', $q)
                   ->orWhere('broj', 'ilike', $q)
                   ->orWhere('adresa', 'ilike', $q);
            });
        }

        $dojave = $dojaveQuery
            ->orderByRaw("CASE prioritet 
                WHEN 'kriticna' THEN 1 
                WHEN 'visoka' THEN 2 
                WHEN 'standardna' THEN 3 
                ELSE 4 
            END")
            ->latest('vrijeme_zaprimanja')
            ->get();

        $intervencije = $intervencijeQuery
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

        $sviDojave = Dojava::whereIn('status', ['zaprimljena', 'dodijeljena', 'u_tijeku'])->count();
        $sveIntervencije = Intervencija::where('status', 'aktivna')->count();

        $brojKritickih = Dojava::whereIn('status', ['zaprimljena', 'dodijeljena', 'u_tijeku'])
            ->where('prioritet', 'kriticna')->count();
        $brojAktivnihIntervencija = $sveIntervencije;

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
            'brojDojava' => $sviDojave,
            'brojIntervencija' => $sveIntervencije,
            'brojTimova' => Tim::where('trenutni_status', '!=', 'raspusten')->count(),
            'odabranaDojava' => $this->odabranaDojava,
            'odabranaIntervencija' => $this->odabranaIntervencija,
            'liveStream' => $this->liveStream,
            'upravljaniTim' => $this->upravljaniTim,
        ];
    }
}