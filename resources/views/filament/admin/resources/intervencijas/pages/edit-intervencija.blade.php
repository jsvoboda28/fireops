<x-filament-panels::page>
    @php
        $intervencija = $this->record;
        $timovi = $this->timovi;
        $timeline = $this->timeline;
        $dojaveVezane = $this->dojaveVezane;
        $brojClanovaUkupno = $this->brojClanovaUkupno;
        $brojVozilaUkupno = $this->brojVozilaUkupno;
        $brojAktivnihTimova = $this->brojAktivnihTimova;
        
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
        .fo-card { transition: all 0.2s; }
        .fo-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .fo-btn { transition: all 0.15s; cursor: pointer; }
        .fo-btn:hover { transform: translateY(-1px); }
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
    </style>

    <div style="position: sticky; top: 0; z-index: 10; background: linear-gradient(135deg, {{ $prio['bg'] }} 0%, {{ $prio['bg2'] }} 100%); color: white; padding: 14px 20px; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.15); margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
            
            <div style="flex: 1; min-width: 280px;">
                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 4px;">
                    <span style="font-size: 10px; font-weight: 800; background: rgba(255,255,255,0.2); padding: 3px 8px; border-radius: 4px;">
                        {{ $prio['label'] }}
                    </span>
                    <span style="font-size: 10px; font-weight: 800; background: rgba(0,0,0,0.3); padding: 3px 8px; border-radius: 4px;">
                        {{ $statusLabel }}
                    </span>
                    <span style="font-size: 11px; opacity: 0.85;">
                        #{{ $intervencija->broj }} • Traje: {{ $intervencija->vrijeme_otvaranja->diffForHumans(null, true, true) }}
                    </span>
                </div>
                <div style="font-size: 18px; font-weight: 800; line-height: 1.2;">{{ $intervencija->naziv }}</div>
                <div style="font-size: 11px; opacity: 0.85; margin-top: 2px;">
                    📍 {{ \Illuminate\Support\Str::limit($intervencija->adresa ?? '—', 60) }}
                    • {{ $intervencija->jls?->naziv ?? '—' }}
                </div>
            </div>

            <div style="display: flex; gap: 6px;">
                <div style="background: rgba(255,255,255,0.18); padding: 6px 12px; border-radius: 8px; min-width: 60px; text-align: center;">
                    <div style="font-size: 20px; font-weight: 900; line-height: 1;">{{ $brojAktivnihTimova }}</div>
                    <div style="font-size: 9px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Timova</div>
                </div>
                <div style="background: rgba(255,255,255,0.18); padding: 6px 12px; border-radius: 8px; min-width: 60px; text-align: center;">
                    <div style="font-size: 20px; font-weight: 900; line-height: 1;">{{ $brojClanovaUkupno }}</div>
                    <div style="font-size: 9px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Ljudi</div>
                </div>
                <div style="background: rgba(255,255,255,0.18); padding: 6px 12px; border-radius: 8px; min-width: 60px; text-align: center;">
                    <div style="font-size: 20px; font-weight: 900; line-height: 1;">{{ $brojVozilaUkupno }}</div>
                    <div style="font-size: 9px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Vozila</div>
                </div>
                <div style="background: rgba(255,255,255,0.18); padding: 6px 12px; border-radius: 8px; min-width: 60px; text-align: center;">
                    <div style="font-size: 20px; font-weight: 900; line-height: 1;">{{ $dojaveVezane->count() }}</div>
                    <div style="font-size: 9px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Dojava</div>
                </div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 16px; padding-bottom: 100px;">
        
        <div>
            @if($timovi->isEmpty())
                <div style="background: white; border: 2px dashed #D1D5DB; border-radius: 14px; padding: 60px 30px; text-align: center;">
                    <div style="font-size: 64px; margin-bottom: 14px;">🚒</div>
                    <div style="font-size: 20px; font-weight: 800; color: #374151; margin-bottom: 8px;">
                        Spremni za rad
                    </div>
                    <div style="font-size: 14px; color: #6B7280; max-width: 400px; margin: 0 auto 24px auto;">
                        Klikni zeleni gumb dolje desno da formiraš prvi tim.
                    </div>
                    <div style="font-size: 36px;">↘</div>
                </div>
            @else
                @if($timoviNaTerenu->isNotEmpty())
                    <div style="margin-bottom: 20px;">
                        <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #DC2626; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            🔥 Na terenu
                            <span style="background: #FEE2E2; color: #991B1B; padding: 2px 8px; border-radius: 10px; font-size: 11px;">{{ $timoviNaTerenu->count() }}</span>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            @foreach($timoviNaTerenu as $tim)
                                @include('filament.admin.resources.intervencijas.pages._tim-kartica', ['tim' => $tim])
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($timoviSpremni->isNotEmpty())
                    <div style="margin-bottom: 20px;">
                        <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #F59E0B; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            ⏳ Spremni za polazak
                            <span style="background: #FEF3C7; color: #92400E; padding: 2px 8px; border-radius: 10px; font-size: 11px;">{{ $timoviSpremni->count() }}</span>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            @foreach($timoviSpremni as $tim)
                                @include('filament.admin.resources.intervencijas.pages._tim-kartica', ['tim' => $tim])
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($timoviPovratak->isNotEmpty())
                    <div style="margin-bottom: 20px;">
                        <div style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #10B981; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            ✅ Završili / povratak
                            <span style="background: #D1FAE5; color: #065F46; padding: 2px 8px; border-radius: 10px; font-size: 11px;">{{ $timoviPovratak->count() }}</span>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            @foreach($timoviPovratak as $tim)
                                @include('filament.admin.resources.intervencijas.pages._tim-kartica', ['tim' => $tim])
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($timoviRaspusteni->isNotEmpty())
                    <details style="margin-bottom: 20px;">
                        <summary style="cursor: pointer; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: #6B7280; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            🚪 Raspušteni
                            <span style="background: #F3F4F6; color: #6B7280; padding: 2px 8px; border-radius: 10px; font-size: 11px;">{{ $timoviRaspusteni->count() }}</span>
                            <span style="margin-left: auto; font-size: 11px; color: #9CA3AF;">▼ klikni za prikaz</span>
                        </summary>
                        <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 8px;">
                            @foreach($timoviRaspusteni as $tim)
                                @include('filament.admin.resources.intervencijas.pages._tim-kartica', ['tim' => $tim])
                            @endforeach
                        </div>
                    </details>
                @endif
            @endif

            @if($dojaveVezane->isNotEmpty())
                <div style="margin-top: 24px; background: white; border-radius: 12px; padding: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #E5E7EB;">
                    <div style="font-size: 13px; font-weight: 800; color: #111827; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                        📞 Dojave vezane
                        <span style="background: #FEE2E2; color: #991B1B; padding: 2px 8px; border-radius: 10px; font-size: 11px;">
                            {{ $dojaveVezane->count() }}
                        </span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 4px;">
                        @foreach($dojaveVezane as $d)
                            @php
                                $prioBoja = match($d->prioritet) {
                                    'kriticna' => '#DC2626',
                                    'visoka' => '#F59E0B',
                                    default => '#10B981',
                                };
                            @endphp
                            <a href="/admin/dojavas/{{ $d->id }}/edit" 
                               class="fo-btn"
                               style="text-decoration: none; color: inherit; background: #F9FAFB; border-left: 3px solid {{ $prioBoja }}; border-radius: 6px; padding: 8px 12px; display: flex; align-items: center; gap: 8px;">
                                <span style="font-size: 12px; font-weight: 700; color: {{ $prioBoja }};">#{{ $d->broj_dojave }}</span>
                                <span style="font-size: 12px; color: #374151;">{{ \Illuminate\Support\Str::limit($d->adresa, 50) }}</span>
                                <span style="font-size: 10px; color: #6B7280; margin-left: auto;">{{ $d->vrijeme_zaprimanja->format('H:i') }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div style="background: white; border-radius: 12px; padding: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; height: fit-content; position: sticky; top: 110px;">
            <div style="font-size: 13px; font-weight: 800; color: #111827; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px solid #F3F4F6; display: flex; align-items: center; gap: 6px;">
                📋 Timeline
                <span style="background: #FEE2E2; color: #991B1B; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 700;">
                    {{ $timeline->count() }}
                </span>
            </div>

            @if($timeline->isEmpty())
                <div style="text-align: center; padding: 30px 10px; color: #9CA3AF;">
                    <div style="font-size: 32px; margin-bottom: 6px;">📜</div>
                    <div style="font-size: 11px;">Timeline će se popunjavati</div>
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 5px; max-height: calc(100vh - 200px); overflow-y: auto;">
                    @foreach($timeline as $log)
                        @php
                            $tipIkona = match($log->status) {
                                'formiran' => '🆕',
                                'polazak' => '🚒',
                                'na_mjestu' => '📍',
                                'intervencija_zavrsena' => '✅',
                                'povratak' => '↩️',
                                'odmor' => '😴',
                                'cekanje_u_bazi' => '🏠',
                                'raspusten' => '🚪',
                                default => '📌',
                            };
                            $boja = match($log->status) {
                                'na_mjestu' => '#DC2626',
                                'polazak' => '#3B82F6',
                                'intervencija_zavrsena' => '#10B981',
                                'povratak' => '#6366F1',
                                default => '#6B7280',
                            };
                        @endphp
                        <div style="border-left: 3px solid {{ $boja }}; padding: 6px 10px; background: #F9FAFB; border-radius: 4px;">
                            <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 2px;">
                                <span style="font-size: 12px;">{{ $tipIkona }}</span>
                                <span style="font-size: 9px; font-weight: 800; color: {{ $boja }}; text-transform: uppercase;">
                                    {{ str_replace('_', ' ', $log->status) }}
                                </span>
                                <span style="font-size: 10px; color: #6B7280; margin-left: auto;">
                                    {{ $log->vrijeme->format('H:i') }}
                                </span>
                            </div>
                            <div style="font-size: 11px; color: #111827; font-weight: 700;">
                                {{ $log->tim?->naziv ?? 'Tim' }}
                            </div>
                            @if($log->autor)
                                <div style="font-size: 9px; color: #9CA3AF; margin-top: 2px;">— {{ $log->autor->name }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div style="position: fixed; bottom: 24px; right: 24px; z-index: 20; display: flex; flex-direction: column; gap: 10px; align-items: flex-end;">
        
        <button type="button" onclick="window.fireopsKlikGumb('dodajDojavu')"
                title="Dodaj postojeću dojavu"
                style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: white; border: none; padding: 14px 18px; border-radius: 50px; font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.4); display: flex; align-items: center; gap: 8px;">
            📞 Dodaj dojavu
        </button>

        <button type="button" onclick="window.fireopsKlikGumb('posaljiTim')"
                title="Pošalji postojeći tim"
                style="background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%); color: white; border: none; padding: 14px 18px; border-radius: 50px; font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 14px rgba(59, 130, 246, 0.4); display: flex; align-items: center; gap: 8px;">
            🚒 Pošalji tim
        </button>

        <button type="button" onclick="window.fireopsKlikGumb('noviTim')"
                title="Formiraj novi tim"
                style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); color: white; border: none; padding: 18px 24px; border-radius: 50px; font-size: 15px; font-weight: 800; cursor: pointer; box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4); display: flex; align-items: center; gap: 8px;">
            ➕ Novi tim
        </button>

        <button type="button" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                title="Vrh stranice"
                style="background: rgba(0,0,0,0.7); color: white; border: none; width: 44px; height: 44px; border-radius: 50%; font-size: 18px; cursor: pointer; box-shadow: 0 4px 14px rgba(0,0,0,0.2);">
            ↑
        </button>
    </div>

    @push('scripts')
        <script>
            window.fireopsKlikGumb = function(akcija) {
                const gumbi = document.querySelectorAll('button');
                for (const g of gumbi) {
                    const txt = (g.innerText || g.textContent || '').toLowerCase();
                    if (akcija === 'noviTim' && txt.includes('novi tim')) {
                        g.click();
                        return;
                    }
                    if (akcija === 'dodajDojavu' && txt.includes('dodaj dojavu')) {
                        g.click();
                        return;
                    }
                    if (akcija === 'posaljiTim' && txt.includes('pošalji tim')) {
                        g.click();
                        return;
                    }
                }
                console.warn('FireOps: gumb nije pronaden za akciju', akcija);
            };
        </script>
    @endpush

    <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #F3F4F6;">
        <details>
            <summary style="cursor: pointer; font-size: 13px; font-weight: 700; color: #6B7280; padding: 8px 0;">
                ⚙ Uredi podatke intervencije
            </summary>
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
