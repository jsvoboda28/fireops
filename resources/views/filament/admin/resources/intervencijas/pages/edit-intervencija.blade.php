<x-filament-panels::page>
    @php
        $intervencija = $this->record;
        $timovi = $this->timovi;
        $timeline = $this->timeline;
        $dojaveVezane = $this->dojaveVezane;
        $brojClanovaUkupno = $this->brojClanovaUkupno;
        $brojVozilaUkupno = $this->brojVozilaUkupno;
        $brojAktivnihTimova = $this->brojAktivnihTimova;
        
        $prio = match($intervencija->prioritet) {
            'kriticna' => ['bg' => 'linear-gradient(135deg, #DC2626 0%, #991B1B 100%)', 'label' => '🔴 KRITIČNA'],
            'visoka' => ['bg' => 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)', 'label' => '🟡 VISOKA'],
            default => ['bg' => 'linear-gradient(135deg, #10B981 0%, #059669 100%)', 'label' => '🟢 STANDARDNA'],
        };
        $statusLabel = match($intervencija->status) {
            'aktivna' => '🔥 AKTIVNA',
            'zatvorena' => '✅ Zatvorena',
            'otkazana' => '❌ Otkazana',
            default => strtoupper($intervencija->status),
        };
    @endphp

    <div>
        {{-- ===== HEADER ===== --}}
        <div style="background: {{ $prio['bg'] }}; color: white; padding: 20px 24px; border-radius: 14px; box-shadow: 0 10px 25px rgba(220, 38, 38, 0.2); margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: start; gap: 20px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 250px;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                        <span style="font-size: 11px; font-weight: 800; background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 4px; letter-spacing: 0.5px;">
                            {{ $prio['label'] }}
                        </span>
                        <span style="font-size: 11px; font-weight: 800; background: rgba(0,0,0,0.3); padding: 4px 10px; border-radius: 4px;">
                            {{ $statusLabel }}
                        </span>
                    </div>
                    <div style="font-size: 24px; font-weight: 900; line-height: 1.2;">{{ $intervencija->naziv }}</div>
                    <div style="font-size: 13px; opacity: 0.9; margin-top: 6px;">
                        <strong>#{{ $intervencija->broj }}</strong> 
                        • 📍 {{ $intervencija->adresa ?? '—' }}
                        • {{ $intervencija->jls?->naziv ?? '—' }}
                    </div>
                    <div style="font-size: 12px; opacity: 0.85; margin-top: 4px;">
                        Otvorena: {{ $intervencija->vrijeme_otvaranja->format('d.m.Y H:i') }}
                        • Traje: {{ $intervencija->vrijeme_otvaranja->diffForHumans(null, true, true) }}
                    </div>
                </div>

                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <div style="background: rgba(255,255,255,0.15); padding: 10px 16px; border-radius: 8px; min-width: 80px; text-align: center;">
                        <div style="font-size: 26px; font-weight: 900; line-height: 1;">{{ $brojAktivnihTimova }}</div>
                        <div style="font-size: 10px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Timova</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); padding: 10px 16px; border-radius: 8px; min-width: 80px; text-align: center;">
                        <div style="font-size: 26px; font-weight: 900; line-height: 1;">{{ $brojClanovaUkupno }}</div>
                        <div style="font-size: 10px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Vatrogasaca</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); padding: 10px 16px; border-radius: 8px; min-width: 80px; text-align: center;">
                        <div style="font-size: 26px; font-weight: 900; line-height: 1;">{{ $brojVozilaUkupno }}</div>
                        <div style="font-size: 10px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Vozila</div>
                    </div>
                    <div style="background: rgba(255,255,255,0.15); padding: 10px 16px; border-radius: 8px; min-width: 80px; text-align: center;">
                        <div style="font-size: 26px; font-weight: 900; line-height: 1;">{{ $dojaveVezane->count() }}</div>
                        <div style="font-size: 10px; opacity: 0.9; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Dojava</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== GLAVNI LAYOUT ===== --}}
        <div style="display: grid; grid-template-columns: 1fr 360px; gap: 20px;">
            
            <div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px;">
                    <div style="font-size: 18px; font-weight: 900; color: #111827; display: flex; align-items: center; gap: 10px;">
                        🚒 Timovi na intervenciji
                        <span style="background: #FEE2E2; color: #991B1B; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: 700;">
                            {{ $timovi->count() }}
                        </span>
                    </div>
                    <div style="font-size: 12px; color: #6B7280;">Gumb "➕ Novi tim" na vrhu</div>
                </div>

                @if($timovi->isEmpty())
                    <div style="background: #F9FAFB; border: 2px dashed #D1D5DB; border-radius: 14px; padding: 50px 30px; text-align: center;">
                        <div style="font-size: 56px; margin-bottom: 14px;">🚒</div>
                        <div style="font-size: 18px; font-weight: 700; color: #374151; margin-bottom: 8px;">
                            Još nema formiranih timova
                        </div>
                        <div style="font-size: 14px; color: #6B7280; max-width: 400px; margin: 0 auto;">
                            Klikni <strong>"➕ Novi tim"</strong> na vrhu da formiraš prvi tim.
                        </div>
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        @foreach($timovi as $tim)
                            @php
                                $statusBoja = match($tim->trenutni_status) {
                                    'formiran' => ['bg' => '#FEF3C7', 'border' => '#F59E0B', 'text' => '#92400E', 'label' => '🆕 FORMIRAN'],
                                    'polazak' => ['bg' => '#DBEAFE', 'border' => '#3B82F6', 'text' => '#1E40AF', 'label' => '🚒 POLAZAK'],
                                    'na_mjestu' => ['bg' => '#FEE2E2', 'border' => '#DC2626', 'text' => '#991B1B', 'label' => '📍 NA MJESTU'],
                                    'intervencija_zavrsena' => ['bg' => '#D1FAE5', 'border' => '#10B981', 'text' => '#065F46', 'label' => '✅ ZAVRŠILI'],
                                    'povratak' => ['bg' => '#E0E7FF', 'border' => '#6366F1', 'text' => '#3730A3', 'label' => '↩️ POVRATAK'],
                                    'odmor' => ['bg' => '#F3F4F6', 'border' => '#6B7280', 'text' => '#374151', 'label' => '😴 ODMOR'],
                                    'cekanje_u_bazi' => ['bg' => '#F3F4F6', 'border' => '#9CA3AF', 'text' => '#374151', 'label' => '🏠 U BAZI'],
                                    'raspusten' => ['bg' => '#F3F4F6', 'border' => '#9CA3AF', 'text' => '#6B7280', 'label' => '🚪 RASPUŠTEN'],
                                    default => ['bg' => '#F3F4F6', 'border' => '#6B7280', 'text' => '#374151', 'label' => strtoupper($tim->trenutni_status)],
                                };
                                $jeRaspusten = $tim->trenutni_status === 'raspusten';
                            @endphp
                            
                            <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; border-left: 6px solid {{ $statusBoja['border'] }}; overflow: hidden; {{ $jeRaspusten ? 'opacity: 0.5;' : '' }}">
                                
                                <div style="padding: 14px 16px; background: {{ $statusBoja['bg'] }}; border-bottom: 1px solid #E5E7EB;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap;">
                                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                            <span style="font-size: 18px; font-weight: 900; color: #111827;">{{ $tim->naziv }}</span>
                                            <span style="font-size: 11px; font-weight: 800; background: {{ $statusBoja['border'] }}; color: white; padding: 4px 10px; border-radius: 4px;">
                                                {{ $statusBoja['label'] }}
                                            </span>
                                        </div>
                                        <a href="/admin/tims/{{ $tim->id }}/edit" 
                                           style="background: white; color: #374151; border: 1px solid #D1D5DB; padding: 5px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; text-decoration: none;">
                                            ⚙ Upravljaj
                                        </a>
                                    </div>
                                    <div style="font-size: 12px; color: {{ $statusBoja['text'] }}; margin-top: 6px;">
                                        🏠 <strong>{{ $tim->bazaPostrojba?->naziv ?? '—' }}</strong>
                                        • 👑 {{ $tim->zapovjednik?->puno_ime ?? '—' }}
                                        • Formiran: {{ $tim->vrijeme_formiranja->format('H:i') }} ({{ $tim->vrijeme_formiranja->diffForHumans(null, true, true) }})
                                    </div>
                                </div>

                                <div style="padding: 14px 16px;">
                                    @if($tim->zadatak)
                                        <div style="background: #FFFBEB; border-left: 3px solid #F59E0B; padding: 8px 12px; border-radius: 4px; font-size: 12px; color: #92400E; margin-bottom: 12px;">
                                            <strong>Zadatak:</strong> {{ $tim->zadatak }}
                                        </div>
                                    @endif

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                                        <div style="background: #F9FAFB; border-radius: 6px; padding: 10px;">
                                            <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #6B7280; margin-bottom: 6px; letter-spacing: 0.5px;">
                                                🧑‍🚒 Članovi ({{ $tim->trenutniClanovi->count() }})
                                            </div>
                                            @if($tim->trenutniClanovi->isEmpty())
                                                <div style="font-size: 11px; color: #9CA3AF; font-style: italic;">Bez članova</div>
                                            @else
                                                <div style="display: flex; flex-direction: column; gap: 3px;">
                                                    @foreach($tim->trenutniClanovi as $clan)
                                                        <div style="display: flex; align-items: center; gap: 4px; font-size: 11px; color: #374151;">
                                                            <span>{{ $clan->uloga === 'zapovjednik' ? '👑' : '👤' }}</span>
                                                            <span style="font-weight: 600;">{{ $clan->vatrogasac->prezime }} {{ $clan->vatrogasac->ime }}</span>
                                                            <span style="color: #9CA3AF; font-size: 10px;">{{ $clan->vatrogasac->postrojba?->skraceni_naziv ?? '?' }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <div style="background: #FFFBEB; border-radius: 6px; padding: 10px;">
                                            <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #92400E; margin-bottom: 6px; letter-spacing: 0.5px;">
                                                🚒 Vozila ({{ $tim->trenutnaVozila->count() }})
                                            </div>
                                            @if($tim->trenutnaVozila->isEmpty())
                                                <div style="font-size: 11px; color: #9CA3AF; font-style: italic;">Bez vozila</div>
                                            @else
                                                <div style="display: flex; flex-direction: column; gap: 3px;">
                                                    @foreach($tim->trenutnaVozila as $tv)
                                                        <div style="display: flex; align-items: center; gap: 4px; font-size: 11px; color: #374151;">
                                                            <span>🚒</span>
                                                            <span style="font-weight: 700;">{{ $tv->vozilo->registracija ?? '?' }}</span>
                                                            <span style="color: #9CA3AF; font-size: 10px;">{{ $tv->vozilo->postrojba?->skraceni_naziv ?? '?' }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if(!$jeRaspusten)
                                        <div style="display: flex; gap: 6px; flex-wrap: wrap; padding-top: 10px; border-top: 1px solid #F3F4F6;">
                                            @if(in_array($tim->trenutni_status, ['formiran', 'cekanje_u_bazi', 'odmor']))
                                                <button wire:click="timPolazak({{ $tim->id }})"
                                                        style="background: #3B82F6; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer;">
                                                    🚒 Polazak
                                                </button>
                                            @endif

                                            @if($tim->trenutni_status === 'polazak')
                                                <button wire:click="timNaMjestu({{ $tim->id }})"
                                                        style="background: #DC2626; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer;">
                                                    📍 Na mjestu
                                                </button>
                                            @endif

                                            @if($tim->trenutni_status === 'na_mjestu')
                                                <button wire:click="timZavrsili({{ $tim->id }})"
                                                        style="background: #10B981; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer;">
                                                    ✅ Završili
                                                </button>
                                            @endif

                                            @if(in_array($tim->trenutni_status, ['intervencija_zavrsena', 'na_mjestu']))
                                                <button wire:click="timPovratak({{ $tim->id }})"
                                                        style="background: #6366F1; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer;">
                                                    ↩️ Povratak
                                                </button>
                                            @endif

                                            <a href="/admin/tims/{{ $tim->id }}/edit"
                                               style="background: #F3F4F6; color: #374151; border: 1px solid #D1D5DB; padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; text-decoration: none; margin-left: auto;">
                                                ➕ Dodaj članove / vozila
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($dojaveVezane->isNotEmpty())
                    <div style="margin-top: 24px;">
                        <div style="font-size: 16px; font-weight: 800; color: #111827; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                            📞 Dojave vezane na intervenciju
                            <span style="background: #FEE2E2; color: #991B1B; padding: 2px 10px; border-radius: 10px; font-size: 12px;">
                                {{ $dojaveVezane->count() }}
                            </span>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 6px;">
                            @foreach($dojaveVezane as $d)
                                <a href="/admin/dojavas/{{ $d->id }}/edit" style="text-decoration: none; color: inherit; background: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 8px 12px; display: flex; align-items: center; gap: 10px;">
                                    <span style="font-size: 12px; font-weight: 700; color: #DC2626;">#{{ $d->broj_dojave }}</span>
                                    <span style="font-size: 12px; color: #374151;">{{ \Illuminate\Support\Str::limit($d->adresa, 60) }}</span>
                                    <span style="font-size: 11px; color: #6B7280; margin-left: auto;">{{ $d->vrijeme_zaprimanja->format('H:i') }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div style="background: white; border-radius: 12px; padding: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; height: fit-content; position: sticky; top: 20px;">
                <div style="font-size: 14px; font-weight: 800; color: #111827; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px solid #F3F4F6; display: flex; align-items: center; gap: 6px;">
                    📋 Timeline intervencije
                    <span style="background: #FEE2E2; color: #991B1B; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: 700;">
                        {{ $timeline->count() }}
                    </span>
                </div>

                @if($timeline->isEmpty())
                    <div style="text-align: center; padding: 30px 10px; color: #9CA3AF;">
                        <div style="font-size: 32px; margin-bottom: 6px;">📜</div>
                        <div style="font-size: 12px;">Timeline će se popunjavati</div>
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; gap: 6px; max-height: 700px; overflow-y: auto;">
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
                            @endphp
                            <div style="border-left: 3px solid #DC2626; padding: 7px 10px; background: #FEF2F2; border-radius: 4px;">
                                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
                                    <span style="font-size: 13px;">{{ $tipIkona }}</span>
                                    <span style="font-size: 10px; font-weight: 800; color: #991B1B; text-transform: uppercase;">
                                        {{ str_replace('_', ' ', $log->status) }}
                                    </span>
                                    <span style="font-size: 10px; color: #6B7280; margin-left: auto;">
                                        {{ $log->vrijeme->format('d.m. H:i') }}
                                    </span>
                                </div>
                                <div style="font-size: 11px; color: #111827; font-weight: 600;">
                                    {{ $log->tim?->naziv ?? 'Tim' }}
                                </div>
                                @if($log->napomena)
                                    <div style="font-size: 10px; color: #6B7280; margin-top: 2px;">{{ $log->napomena }}</div>
                                @endif
                                @if($log->autor)
                                    <div style="font-size: 9px; color: #9CA3AF; margin-top: 3px;">— {{ $log->autor->name }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #F3F4F6;">
            <details>
                <summary style="cursor: pointer; font-size: 14px; font-weight: 700; color: #374151; padding: 10px 0;">
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
    </div>
</x-filament-panels::page>
