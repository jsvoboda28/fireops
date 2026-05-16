<?php

namespace App\Filament\Admin\Pages;

use App\Models\Dojava;
use App\Models\Intervencija;
use App\Models\Postrojba;
use App\Models\Tim;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;

class OperativaKarta extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map';
    
    protected static ?string $navigationLabel = 'Operativna karta';
    
    protected static ?string $title = 'Operativna karta';
    
    protected static ?int $navigationSort = -9;

    protected string $view = 'filament.admin.pages.operativa-karta';

    protected static ?string $slug = 'operativa';

    public Width|string|null $maxContentWidth = Width::Full;

    public function getViewData(): array
    {
        $postrojbe = Postrojba::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('aktivna', true)
            ->with('jls')
            ->get();

        // Samo dojave KOJE NISU vezane na intervenciju
        $dojave = Dojava::query()
            ->whereNull('intervencija_id')
            ->whereIn('status', ['zaprimljena', 'dodijeljena', 'u_tijeku'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['jls'])
            ->get();

        $intervencije = Intervencija::query()
            ->where('status', 'aktivna')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['jls', 'timovi.zapovjednik'])
            ->get();

        $timoviNaTerenu = Tim::query()
            ->whereIn('trenutni_status', ['polazak', 'na_mjestu', 'povratak'])
            ->whereNotNull('intervencija_id')
            ->with(['intervencija', 'bazaPostrojba', 'zapovjednik', 'trenutniClanovi'])
            ->get();

        $stanje = $this->izracunajStanje($intervencije->count(), $dojave->where('prioritet', 'kriticna')->count());

        $mapaData = [
            'postrojbe' => $postrojbe->map(fn($p) => [
                'id' => $p->id,
                'naziv' => $p->naziv,
                'tip' => $p->tip ?? 'ostalo',
                'lat' => (float) $p->latitude,
                'lng' => (float) $p->longitude,
                'operativna' => (bool) ($p->operativno_spremna ?? false),
                'adresa' => $p->adresa ?? '—',
                'jls' => $p->jls?->naziv ?? '—',
            ])->values()->toArray(),
            
            'dojave' => $dojave->map(fn($d) => [
                'id' => $d->id,
                'broj' => $d->broj_dojave,
                'adresa' => $d->adresa,
                'prioritet' => $d->prioritet,
                'tip' => $d->tip_nepogode,
                'status' => $d->status,
                'jls' => $d->jls?->naziv ?? '—',
                'vrijeme' => $d->vrijeme_zaprimanja->format('d.m.Y H:i'),
                'lat' => (float) $d->latitude,
                'lng' => (float) $d->longitude,
            ])->values()->toArray(),
            
            'intervencije' => $intervencije->map(fn($i) => [
                'id' => $i->id,
                'broj' => $i->broj,
                'naziv' => $i->naziv,
                'prioritet' => $i->prioritet,
                'tip' => $i->tip_intervencije,
                'jls' => $i->jls?->naziv ?? '—',
                'adresa' => $i->adresa,
                'lat' => (float) $i->latitude,
                'lng' => (float) $i->longitude,
                'vrijeme' => $i->vrijeme_otvaranja->format('d.m.Y H:i'),
                'brojTimova' => $i->timovi->where('trenutni_status', '!=', 'raspusten')->count(),
            ])->values()->toArray(),

            'timovi' => $timoviNaTerenu->map(function($t) {
                $intLat = $t->intervencija?->latitude;
                $intLng = $t->intervencija?->longitude;
                $bazaLat = $t->bazaPostrojba?->latitude;
                $bazaLng = $t->bazaPostrojba?->longitude;
                
                $lat = null;
                $lng = null;
                
                if ($t->trenutni_status === 'polazak') {
                    $lat = $bazaLat ?? $intLat;
                    $lng = $bazaLng ?? $intLng;
                } elseif ($t->trenutni_status === 'na_mjestu') {
                    $lat = $intLat;
                    $lng = $intLng;
                } elseif ($t->trenutni_status === 'povratak') {
                    if ($bazaLat && $intLat) {
                        $lat = ($bazaLat + $intLat) / 2;
                        $lng = ($bazaLng + $intLng) / 2;
                    } else {
                        $lat = $intLat ?? $bazaLat;
                        $lng = $intLng ?? $bazaLng;
                    }
                }
                
                if (!$lat || !$lng) return null;
                
                return [
                    'id' => $t->id,
                    'naziv' => $t->naziv,
                    'status' => $t->trenutni_status,
                    'zapovjednik' => $t->zapovjednik?->puno_ime ?? '—',
                    'brojClanova' => $t->trenutniClanovi->count(),
                    'intervencija' => $t->intervencija?->naziv,
                    'intervencijaId' => $t->intervencija_id,
                    'bazaPostrojba' => $t->bazaPostrojba?->naziv ?? '—',
                    'lat' => (float) $lat,
                    'lng' => (float) $lng,
                ];
            })->filter()->values()->toArray(),
        ];

        return [
            'stanje' => $stanje,
            'mapaData' => json_encode($mapaData, JSON_UNESCAPED_UNICODE),
            'brojPostrojbi' => $postrojbe->count(),
            'brojDojava' => $dojave->count(),
            'brojIntervencija' => $intervencije->count(),
            'brojTimovaNaTerenu' => $timoviNaTerenu->count(),
        ];
    }

    protected function izracunajStanje(int $brojIntervencija, int $brojKritickihDojava): array
    {
        if ($brojKritickihDojava > 0 || $brojIntervencija >= 5) {
            return ['naslov' => 'KRITIČNO STANJE', 'boja' => '#DC2626'];
        } elseif ($brojIntervencija > 0) {
            return ['naslov' => 'AKTIVNO STANJE', 'boja' => '#F97316'];
        }
        return ['naslov' => 'PRIPRAVNOST', 'boja' => '#059669'];
    }
}