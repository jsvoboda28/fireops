<x-filament-panels::page>
    @php
        $intervencija = $this->record;
        $timovi = $this->timovi;
        $timeline = $this->timeline;
        $dojaveVezane = $this->dojaveVezane;
        $brojClanovaUkupno = $this->brojClanovaUkupno;
        $brojVozilaUkupno = $this->brojVozilaUkupno;
        $brojAktivnihTimova = $this->brojAktivnihTimova;
        $rezervacijeZaIntervenciju = $this->rezervacijeZaOvuIntervenciju;
        
        $timoviNaTerenu = $timovi->whereIn('trenutni_status', ['polazak', 'na_mjestu']);
        $timoviSpremni = $timovi->whereIn('trenutni_status', ['formiran', 'cekanje_u_bazi']);
        $timoviPovratak = $timovi->whereIn('trenutni_status', ['povratak', 'intervencija_zavrsena', 'odmor']);
        $timoviRaspusteni = $timovi->where('trenutni_status', 'raspusten');
        
        $prio = match($intervencija->prioritet) {
            'kriticna' => ['bg' => '#DC2626', 'bg2' => '#991B1B', 'label' => '🔴 KRITIČNA'],
            'visoka' => ['bg' => '#F59E0B', 'bg2' => '#D97706', 'label' => '🟡 VISOKA'],
            default => ['bg' => '#10B981', 'bg2' => '#059669', 'label' => '🟢 STANDARDNA'],
        };
        $statusLabel = match($intervencija->status) {
            'aktivna' => '🔥 AKTIVNA',
            'zatvorena' => '✅ Zatvorena',
            'otkazana' => '❌ Otkazana',
            default => strtoupper($intervencija->status),
        };
    @endphp

    <style>
        .fo-card { transition: all 0.2s ease; }
        .fo-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .fo-btn { transition: all 0.15s ease; cursor: pointer; }
        .fo-btn:hover { transform: translateY(-1px); }
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
        
        .fo-action-card { 
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); 
            cursor: pointer; 
            border: none; 
            padding: 22px 18px; 
            border-radius: 16px; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            gap: 8px; 
            text-align: center; 
            position: relative;
            overflow: hidden;
        }
        .fo-action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 100%);
            pointer-events: none;
        }
        .fo-action-card:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 14px 32px rgba(0,0,0,0.18); 
        }
        .fo-action-ikona { font-size: 30px; line-height: 1; }
        .fo-action-naslov { font-size: 15px; font-weight: 800; letter-spacing: -0.2px; }
        .fo-action-opis { font-size: 11px; opacity: 0.92; line-height: 1.35; max-width: 180px; font-weight: 500; }

        /* Sakrij gumbe akcija u Filament headeru — premjesteni su u našu karticu */
        header.fi-header button {
            display: none !important;
        }
    </style>

    {{-- ===== HEADER ===== --}}
    <div style="background: linear-gradient(135deg, {{ $prio['bg'] }} 0%, {{ $prio['bg2'] }} 100%); color: white; padding: 18px 22px; border-radius: 14px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); margin-bottom: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 280px;">
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 6px;">
                    <span style="font-size: 10px; font-weight: 800; background: rgba(255,255,255,0.22); padding: 4px 10px; border-radius: 6px; letter-spacing: 0.5px;">{{ $prio['label'] }}</span>
                    <span style="font-size: 10px; font-weight: 800; background: rgba(0,0,0,0.3); padding: 4px 10px; border-radius: 6px;">{{ $statusLabel }}</span>
                    <span style="font-size: 12px; opacity: 0.9;">#{{ $intervencija->broj }} • Traje: {{ $intervencija->vrijeme_otvaranja->diffForHumans(null, true, true) }}</span>
                </div>
                <div style="font-size: 22px; font-weight: 800; line-height: 1.2; letter-spacing: -0.5px;">{{ $intervencija->naziv }}</div>
                <div style="font-size: 12px; opacity: 0.9; margin-top: 4px;">📍 {{ \Illuminate\Support\Str::limit($intervencija->adresa ?? '—', 60) }} • {{ $intervencija->jls?->naziv ?? '—' }}</div>
            </div>
            <div style="display: flex; gap: 8px;">
                <div style="background: rgba(255,255,255,0.18); padding: 8px 14px; border-radius: 10px; min-width: 70px; text-align: center;">
                    <div style="font-size: 24px; font-weight: 900; line-height: 1;">{{ $brojAktivnihTimova }}</div>
                    <div style="font-size: 9px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.7px; margin-top: 3px; font-weight: 700;">Timova</div>
                </div>
                <div style="background: rgba(255,255,255,0.18); padding: 8px 14px; border-radius: 10px; min-width: 70px; text-align: center;">
                    <div style="font-size: 24px; font-weight: 900; line-height: 1;">{{ $brojClanovaUkupno }}</div>
                    <div style="font-size: 9px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.7px; margin-top: 3px; font-weight: 700;">Ljudi</div>
                </div>
                <div style="background: rgba(255,255,255,0.18); padding: 8px 14px; border-radius: 10px; min-width: 70px; text-align: center;">
                    <div style="font-size: 24px; font-weight: 900; line-height: 1;">{{ $brojVozilaUkupno }}</div>
                    <div style="font-size: 9px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.7px; margin-top: 3px; font-weight: 700;">Vozila</div>
                </div>
                <div style="background: rgba(255,255,255,0.18); padding: 8px 14px; border-radius: 10px; min-width: 70px; text-align: center;">
                    <div style="font-size: 24px; font-weight: 900; line-height: 1;">{{ $dojaveVezane->count() }}</div>
                    <div style="font-size: 9px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.7px; margin-top: 3px; font-weight: 700;">Dojava</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== AKCIJSKA TRAKA ===== --}}
    <div style="background: white; border-radius: 16px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border: 1px solid #E5E7EB; margin-bottom: 20px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
            <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.2px; color: #6B7280;">
                ⚡ Što hoćeš učiniti?
            </div>
            @if($intervencija->status === 'aktivna')
                <button type="button" onclick="window.fireopsKlikGumb('zatvoriIntervenciju')"
                        style="background: white; color: #6B7280; border: 1px solid #D1D5DB; padding: 7px 14px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.15s;"
                        onmouseover="this.style.background='#F3F4F6'; this.style.color='#374151';"
                        onmouseout="this.style.background='white'; this.style.color='#6B7280';">
                    🔒 Zatvori intervenciju
                </button>
            @endif
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
            
            <button type="button" onclick="window.fireopsKlikGumb('noviTim')" class="fo-action-card"
                    style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white;">
                <span class="fo-action-ikona">➕</span>
                <span class="fo-action-naslov">Novi tim</span>
                <span class="fo-action-opis">Formiraj tim od nule</span>
            </button>

            <button type="button" onclick="window.fireopsKlikGumb('posaljiTim')" class="fo-action-card"
                    style="background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%); color: white;">
                <span class="fo-action-ikona">🚒</span>
                <span class="fo-action-naslov">Pošalji tim</span>
                <span class="fo-action-opis">Premjesti postojeći tim</span>
            </button>

            <button type="button" onclick="window.fireopsKlikGumb('rezervirajTim')" class="fo-action-card"
                    style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: white;">
                <span class="fo-action-ikona">📌</span>
                <span class="fo-action-naslov">Rezerviraj tim</span>
                <span class="fo-action-opis">Stavi u red čekanja</span>
            </button>

            <button type="button" onclick="window.fireopsKlikGumb('dodajDojavu')" class="fo-action-card"
                    style="background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%); color: white;">
                <span class="fo-action-ikona">📞</span>
                <span class="fo-action-naslov">Dodaj dojavu</span>
                <span class="fo-action-opis">Spoji s ovom intervencijom</span>
            </button>
        </div>
    </div>

    {{-- ===== GLAVNI LAYOUT ===== --}}
    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 18px;">
        <div>
            @if($timovi->isEmpty())
                <div style="background: white; border: 2px dashed #D1D5DB; border-radius: 16px; padding: 60px 30px; text-align: center;">
                    <div style="font-size: 64px; margin-bottom: 14px;">🚒</div>
                    <div style="font-size: 20px; font-weight: 800; color: #374151; margin-bottom: 8px;">Spremni za rad</div>
                    <div style="font-size: 14px; color: #6B7280; max-width: 400px; margin: 0 auto;">Klikni "➕ Novi tim" gore da formiraš prvi tim.</div>
                </div>
            @else
                @if($timoviNaTerenu->isNotEmpty())
                    <div style="margin-bottom: 22px;">
                        <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #DC2626; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                            🔥 Na terenu
                            <span style="background: #FEE2E2; color: #991B1B; padding: 2px 9px; border-radius: 10px; font-size: 11px;">{{ $timoviNaTerenu->count() }}</span>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            @foreach($timoviNaTerenu as $tim)
                                @include('filament.admin.resources.intervencijas.pages._tim-kartica', ['tim' => $tim])
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($timoviSpremni->isNotEmpty())
                    <div style="margin-bottom: 22px;">
                        <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #F59E0B; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                            ⏳ Spremni za polazak
                            <span style="background: #FEF3C7; color: #92400E; padding: 2px 9px; border-radius: 10px; font-size: 11px;">{{ $timoviSpremni->count() }}</span>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            @foreach($timoviSpremni as $tim)
                                @include('filament.admin.resources.intervencijas.pages._tim-kartica', ['tim' => $tim])
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($timoviPovratak->isNotEmpty())
                    <div style="margin-bottom: 22px;">
                        <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #10B981; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                            ✅ Završili / povratak
                            <span style="background: #D1FAE5; color: #065F46; padding: 2px 9px; border-radius: 10px; font-size: 11px;">{{ $timoviPovratak->count() }}</span>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            @foreach($timoviPovratak as $tim)
                                @include('filament.admin.resources.intervencijas.pages._tim-kartica', ['tim' => $tim])
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($timoviRaspusteni->isNotEmpty())
                    <details style="margin-bottom: 22px;">
                        <summary style="cursor: pointer; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #6B7280; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                            🚪 Raspušteni
                            <span style="background: #F3F4F6; color: #6B7280; padding: 2px 9px; border-radius: 10px; font-size: 11px;">{{ $timoviRaspusteni->count() }}</span>
                            <span style="margin-left: auto; font-size: 11px; color: #9CA3AF;">▼ klikni</span>
                        </summary>
                        <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 10px;">
                            @foreach($timoviRaspusteni as $tim)
                                @include('filament.admin.resources.intervencijas.pages._tim-kartica', ['tim' => $tim])
                            @endforeach
                        </div>
                    </details>
                @endif
            @endif

            @if($rezervacijeZaIntervenciju->isNotEmpty())
                <div style="margin-top: 24px; background: white; border-radius: 14px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border: 1px solid #E5E7EB; border-left: 4px solid #F59E0B;">
                    <div style="font-size: 13px; font-weight: 800; color: #111827; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                        📌 Rezervirani timovi za ovu intervenciju
                        <span style="background: #FEF3C7; color: #92400E; padding: 2px 9px; border-radius: 10px; font-size: 11px;">{{ $rezervacijeZaIntervenciju->count() }}</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        @foreach($rezervacijeZaIntervenciju as $rez)
                            @php $tim = $rez->tim; @endphp
                            <div style="background: #FFFBEB; border-radius: 8px; padding: 10px 12px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                <span style="font-size: 13px; font-weight: 800; color: #92400E;">{{ $tim?->naziv ?? '?' }}</span>
                                @if($tim?->intervencija_id && $tim->intervencija_id !== $intervencija->id && $tim->intervencija)
                                    <span style="font-size: 11px; color: #6B7280;">↪ {{ \Illuminate\Support\Str::limit($tim->intervencija->naziv, 30) }}</span>
                                @else
                                    <span style="font-size: 11px; color: #6B7280;">🏠 u bazi</span>
                                @endif
                                <span style="font-size: 10px; color: #6B7280; margin-left: auto;">Rezervirao: {{ $rez->rezervirao?->name ?? '?' }} u {{ $rez->rezervirano_u->format('H:i') }}</span>
                                <button wire:click="aktivirajRezervaciju({{ $rez->id }})"
                                        wire:confirm="Aktivirati rezervaciju i premjestiti tim na ovu intervenciju?"
                                        style="background: #10B981; color: white; border: none; padding: 5px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer;">
                                    ▶ Aktiviraj sad
                                </button>
                                <button wire:click="otkaziRezervaciju({{ $rez->id }})"
                                        wire:confirm="Otkazati rezervaciju?"
                                        style="background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; padding: 5px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer;">
                                    ✕ Otkaži
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($dojaveVezane->isNotEmpty())
                <div style="margin-top: 24px; background: white; border-radius: 14px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border: 1px solid #E5E7EB;">
                    <div style="font-size: 13px; font-weight: 800; color: #111827; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                        📞 Dojave vezane
                        <span style="background: #FEE2E2; color: #991B1B; padding: 2px 9px; border-radius: 10px; font-size: 11px;">{{ $dojaveVezane->count() }}</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        @foreach($dojaveVezane as $d)
                            @php
                                $prioBoja = match($d->prioritet) {
                                    'kriticna' => '#DC2626', 'visoka' => '#F59E0B', default => '#10B981',
                                };
                            @endphp
                            <a href="/admin/dojavas/{{ $d->id }}/edit" class="fo-btn"
                               style="text-decoration: none; color: inherit; background: #F9FAFB; border-left: 3px solid {{ $prioBoja }}; border-radius: 8px; padding: 10px 14px; display: flex; align-items: center; gap: 10px;">
                                <span style="font-size: 12px; font-weight: 700; color: {{ $prioBoja }};">#{{ $d->broj_dojave }}</span>
                                <span style="font-size: 12px; color: #374151;">{{ \Illuminate\Support\Str::limit($d->adresa, 50) }}</span>
                                <span style="font-size: 10px; color: #6B7280; margin-left: auto;">{{ $d->vrijeme_zaprimanja->format('H:i') }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- DESNO: TIMELINE --}}
        <div style="background: white; border-radius: 14px; padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.04); border: 1px solid #E5E7EB; height: fit-content; position: sticky; top: 20px;">
            <div style="font-size: 13px; font-weight: 800; color: #111827; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 2px solid #F3F4F6; display: flex; align-items: center; gap: 6px;">
                📋 Timeline
                <span style="background: #FEE2E2; color: #991B1B; padding: 2px 9px; border-radius: 10px; font-size: 10px; font-weight: 700;">{{ $timeline->count() }}</span>
            </div>
            @if($timeline->isEmpty())
                <div style="text-align: center; padding: 30px 10px; color: #9CA3AF;">
                    <div style="font-size: 32px; margin-bottom: 6px;">📜</div>
                    <div style="font-size: 11px;">Timeline će se popunjavati</div>
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 6px; max-height: calc(100vh - 200px); overflow-y: auto;">
                    @foreach($timeline as $log)
                        @php
                            $tipIkona = match($log->status) {
                                'formiran' => '🆕', 'polazak' => '🚒', 'na_mjestu' => '📍',
                                'intervencija_zavrsena' => '✅', 'povratak' => '↩️',
                                'odmor' => '😴', 'cekanje_u_bazi' => '🏠', 'raspusten' => '🚪',
                                default => '📌',
                            };
                            $boja = match($log->status) {
                                'na_mjestu' => '#DC2626', 'polazak' => '#3B82F6',
                                'intervencija_zavrsena' => '#10B981', 'povratak' => '#6366F1',
                                default => '#6B7280',
                            };
                        @endphp
                        <div style="border-left: 3px solid {{ $boja }}; padding: 7px 10px; background: #F9FAFB; border-radius: 6px;">
                            <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 2px;">
                                <span style="font-size: 12px;">{{ $tipIkona }}</span>
                                <span style="font-size: 9px; font-weight: 800; color: {{ $boja }}; text-transform: uppercase;">{{ str_replace('_', ' ', $log->status) }}</span>
                                <span style="font-size: 10px; color: #6B7280; margin-left: auto;">{{ $log->vrijeme->format('H:i') }}</span>
                            </div>
                            <div style="font-size: 11px; color: #111827; font-weight: 700;">{{ $log->tim?->naziv ?? 'Tim' }}</div>
                            @if($log->autor)
                                <div style="font-size: 9px; color: #9CA3AF; margin-top: 2px;">— {{ $log->autor->name }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            window.fireopsKlikGumb = function(akcija) {
                const gumbi = document.querySelectorAll('header.fi-header button, button');
                for (const g of gumbi) {
                    const txt = (g.innerText || g.textContent || '').toLowerCase();
                    if (akcija === 'noviTim' && txt.includes('novi tim')) { g.click(); return; }
                    if (akcija === 'dodajDojavu' && txt.includes('dodaj dojavu')) { g.click(); return; }
                    if (akcija === 'posaljiTim' && txt.includes('pošalji tim')) { g.click(); return; }
                    if (akcija === 'rezervirajTim' && txt.includes('rezerviraj tim')) { g.click(); return; }
                    if (akcija === 'zatvoriIntervenciju' && txt.includes('zatvori intervenciju')) { g.click(); return; }
                }
                console.warn('FireOps: gumb nije pronaden za akciju', akcija);
            };
        </script>
    @endpush

    <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #F3F4F6;">
        <details>
            <summary style="cursor: pointer; font-size: 13px; font-weight: 700; color: #6B7280; padding: 8px 0;">⚙ Uredi podatke intervencije</summary>
            <div style="margin-top: 16px;">
                <form wire:submit="save">
                    {{ $this->form }}
                    <div style="display: flex; gap: 12px; margin-top: 20px;">
                        <x-filament::button type="submit">Spremi izmjene</x-filament::button>
                    </div>
                </form>
            </div>
        </details>
    </div>
</x-filament-panels::page>
