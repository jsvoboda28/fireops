@php
    $prio = match($intervencija->prioritet) {
        'kriticna' => ['bg' => 'linear-gradient(135deg, #EF4444 0%, #991B1B 100%)', 'border' => '#DC2626', 'badge' => 'KRITIČNA'],
        'visoka' => ['bg' => 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)', 'border' => '#F59E0B', 'badge' => 'VISOKA'],
        default => ['bg' => 'linear-gradient(135deg, #10B981 0%, #047857 100%)', 'border' => '#10B981', 'badge' => 'STANDARDNA'],
    };
    $aktivniTimovi = $intervencija->timovi->where('trenutni_status', '!=', 'raspusten');
    $brojLjudi = $aktivniTimovi->sum(fn($t) => $t->trenutniClanovi->count());
@endphp

<div style="background: {{ $prio['bg'] }}; padding: 10px 14px; color: white; flex-shrink: 0; position: relative;">
    <button wire:click="zatvoriDetalje"
            style="position: absolute; top: 8px; right: 8px; background: rgba(0,0,0,0.25); color: white; border: none; width: 26px; height: 26px; border-radius: 50%; cursor: pointer; font-size: 12px; font-weight: 800;">
        ✕
    </button>
    
    <div style="display: flex; align-items: center; gap: 8px;">
        <div style="flex: 1; min-width: 0;">
            <div style="font-size: 8px; font-weight: 700; opacity: 0.9; text-transform: uppercase; letter-spacing: 1.2px;">🔥 INTERVENCIJA • {{ $prio['badge'] }}</div>
            <div style="font-size: 15px; font-weight: 900; letter-spacing: -0.2px; line-height: 1.2; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $intervencija->naziv }}</div>
            <div style="font-size: 10px; opacity: 0.9; margin-top: 2px;">#{{ $intervencija->broj }} • {{ $intervencija->jls?->naziv ?? '—' }} • traje {{ $intervencija->vrijeme_otvaranja->diffForHumans(null, true, true) }}</div>
        </div>
        <div style="text-align: right; flex-shrink: 0; padding-right: 24px;">
            <div style="font-size: 18px; font-weight: 900; line-height: 1;">{{ $aktivniTimovi->count() }}<span style="font-size: 11px; opacity: 0.8;">/{{ $brojLjudi }}</span></div>
            <div style="font-size: 8px; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.5px;">Tim/Ljudi</div>
        </div>
    </div>
</div>

<div style="background: #F8FAFC; padding: 6px 10px; border-bottom: 1px solid #E2E8F0; flex-shrink: 0; display: flex; gap: 4px;">
    <button wire:click="otvoriModal('noviTim')"
            style="flex: 1; background: linear-gradient(135deg, #10B981 0%, #047857 100%); color: white; border: none; padding: 7px 6px; border-radius: 6px; font-size: 10px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px;">
        ➕ Novi tim
    </button>
    <button wire:click="otvoriModal('posaljiTim')"
            style="flex: 1; background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%); color: white; border: none; padding: 7px 6px; border-radius: 6px; font-size: 10px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px;">
        🚒 Pošalji
    </button>
    <button wire:click="otvoriModal('rezervirajTim')"
            style="flex: 1; background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: white; border: none; padding: 7px 6px; border-radius: 6px; font-size: 10px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px;">
        📌 Rezerviraj
    </button>
    <button wire:click="otvoriModal('dodajDojavu')"
            style="flex: 1; background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%); color: white; border: none; padding: 7px 6px; border-radius: 6px; font-size: 10px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 4px;">
        📞 Dojava
    </button>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px; padding: 8px; flex: 1; min-height: 0; overflow: hidden;">
    
    <div style="display: flex; flex-direction: column; min-height: 0; overflow: hidden; background: #FAFAFA; border-radius: 8px; border: 1px solid #E2E8F0;">
        
        <div style="padding: 8px 10px; border-bottom: 1px solid #E2E8F0; flex-shrink: 0; background: white; border-radius: 8px 8px 0 0;">
            <div style="display: flex; align-items: center; gap: 6px;">
                <span style="font-size: 14px;">💬</span>
                <span style="font-size: 12px; font-weight: 800; color: #0F172A;">Live Stream</span>
                <span style="background: #DBEAFE; color: #1E40AF; padding: 1px 6px; border-radius: 8px; font-size: 9px; font-weight: 800;">{{ count($liveStream ?? []) }}</span>
                <span style="margin-left: auto; font-size: 9px; color: #64748B; display: flex; align-items: center; gap: 3px; font-weight: 600;">
                    <span class="fo-pulse" style="display: inline-block; width: 5px; height: 5px; background: #10B981; border-radius: 50%;"></span>
                    LIVE
                </span>
            </div>
        </div>
        
        <div class="fo-scroll" style="overflow-y: auto; flex: 1; padding: 8px;">
            @forelse(($liveStream ?? []) as $event)
                <div style="background: white; border: 1px solid #E2E8F0; border-left: 3px solid {{ $event['boja']['border'] }}; border-radius: 6px; padding: 7px 9px; margin-bottom: 5px;">
                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 3px;">
                        <span style="font-size: 12px;">{{ $event['ikona'] }}</span>
                        <span style="font-size: 9px; font-weight: 800; background: {{ $event['boja']['bg'] }}; color: {{ $event['boja']['text'] }}; padding: 2px 5px; border-radius: 3px; text-transform: uppercase; letter-spacing: 0.3px;">{{ $event['naslov'] }}</span>
                        <span style="margin-left: auto; font-size: 9px; color: #94A3B8; font-weight: 700; font-variant-numeric: tabular-nums;">{{ $event['vrijeme']->format('H:i') }}</span>
                    </div>
                    <div style="font-size: 12px; color: #0F172A; line-height: 1.4; font-weight: 600;">{{ $event['sadrzaj'] }}</div>
                    @if(!empty($event['napomena']))
                        <div style="font-size: 11px; color: #475569; line-height: 1.3; margin-top: 2px; font-style: italic;">{{ $event['napomena'] }}</div>
                    @endif
                    <div style="font-size: 9px; color: #94A3B8; margin-top: 3px; display: flex; align-items: center; gap: 4px;">
                        <span>👤</span>
                        <span>{{ $event['autor'] }}</span>
                        @if(!empty($event['napomena_id']))
                            <button wire:click="obrisiNapomenu({{ $event['napomena_id'] }})"
                                    wire:confirm="Sigurno obrisati ovu napomenu?"
                                    style="margin-left: auto; background: transparent; border: none; color: #DC2626; cursor: pointer; font-size: 10px; padding: 0;"
                                    title="Obriši">
                                ✕
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 30px 16px; color: #94A3B8;">
                    <div style="font-size: 28px; margin-bottom: 8px;">💬</div>
                    <div style="font-size: 11px; font-weight: 700; color: #475569;">Nema događaja</div>
                </div>
            @endforelse
        </div>
        
        <div style="background: white; border-top: 1px solid #E2E8F0; padding: 8px; flex-shrink: 0;">
            <div style="display: flex; gap: 3px; margin-bottom: 6px; flex-wrap: wrap;">
                @php
                    $tipovi = [
                        'biljeska' => ['📝', 'Bilješka', '#94A3B8'],
                        'opasnost' => ['⚠', 'Opasnost', '#DC2626'],
                        'radio' => ['📞', 'Radio', '#3B82F6'],
                        'zahtjev' => ['🔧', 'Zahtjev', '#F59E0B'],
                        'akcija' => ['✅', 'Akcija', '#10B981'],
                        'lokacija' => ['📍', 'Lokacija', '#6366F1'],
                    ];
                @endphp
                @foreach($tipovi as $key => $info)
                    <button wire:click="$set('novaNapomenaTip', '{{ $key }}')"
                            style="background: {{ $novaNapomenaTip === $key ? $info[2] : 'white' }}; color: {{ $novaNapomenaTip === $key ? 'white' : '#475569' }}; border: 1px solid {{ $novaNapomenaTip === $key ? $info[2] : '#E2E8F0' }}; padding: 4px 7px; border-radius: 5px; font-size: 10px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 3px;"
                            title="{{ $info[1] }}">
                        <span>{{ $info[0] }}</span>
                        <span style="font-size: 9px;">{{ $info[1] }}</span>
                    </button>
                @endforeach
            </div>
            
            <div style="display: flex; gap: 6px; align-items: flex-end;">
                <textarea wire:model="novaNapomenaSadrzaj"
                          wire:keydown.ctrl.enter="dodajNapomenu"
                          placeholder="Upiši napomenu... (Ctrl+Enter za slanje)"
                          rows="2"
                          style="flex: 1; background: white; border: 1px solid #E2E8F0; border-radius: 6px; padding: 6px 8px; font-size: 11px; color: #0F172A; font-family: inherit; resize: none; outline: none;"></textarea>
                <button wire:click="dodajNapomenu"
                        style="background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%); color: white; border: none; padding: 8px 12px; border-radius: 6px; font-size: 11px; font-weight: 800; cursor: pointer; white-space: nowrap;">
                    Pošalji ➤
                </button>
            </div>
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 8px; min-height: 0; overflow: hidden;">
        
        <div wire:ignore style="height: 40%; min-height: 0; background: #1E40AF; border-radius: 8px; overflow: hidden; border: 1px solid #E2E8F0; position: relative;">
            <div id="fo-mini-mapa-{{ $intervencija->id }}" 
                 data-int-id="{{ $intervencija->id }}"
                 data-int-lat="{{ $intervencija->latitude }}"
                 data-int-lng="{{ $intervencija->longitude }}"
                 data-int-naziv="{{ $intervencija->naziv }}"
                 data-timovi="{{ json_encode($aktivniTimovi->map(fn($t) => [
                     'id' => $t->id,
                     'naziv' => $t->naziv,
                     'status' => $t->trenutni_status,
                     'baza_lat' => $t->bazaPostrojba?->latitude,
                     'baza_lng' => $t->bazaPostrojba?->longitude,
                 ])->values()) }}"
                 style="width: 100%; height: 100%;">
            </div>
            <div style="position: absolute; top: 6px; left: 6px; background: rgba(255,255,255,0.95); padding: 3px 7px; border-radius: 4px; font-size: 9px; font-weight: 800; color: #0F172A; text-transform: uppercase; letter-spacing: 0.5px; z-index: 1000; pointer-events: none;">
                🛰 Satelit
            </div>
        </div>
        
        <div style="flex: 1; min-height: 0; display: flex; flex-direction: column; background: white; border-radius: 8px; border: 1px solid #E2E8F0; overflow: hidden;">
            
            <div style="padding: 8px 10px; border-bottom: 1px solid #E2E8F0; flex-shrink: 0;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-size: 14px;">🚒</span>
                    <span style="font-size: 12px; font-weight: 800; color: #0F172A;">Timovi i resursi</span>
                    <span style="margin-left: auto; font-size: 10px; color: #64748B; font-weight: 700;">
                        {{ $aktivniTimovi->count() }} timova · {{ $brojLjudi }} ljudi
                    </span>
                </div>
            </div>
            
            <div class="fo-scroll" style="overflow-y: auto; flex: 1; padding: 6px;">
                @if($aktivniTimovi->isNotEmpty())
                    @foreach($aktivniTimovi as $tim)
                        @php
                            $statusBoja = match($tim->trenutni_status) {
                                'na_mjestu' => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'border' => '#DC2626', 'label' => '📍 NA MJESTU'],
                                'polazak' => ['bg' => '#DBEAFE', 'text' => '#1E40AF', 'border' => '#3B82F6', 'label' => '🚒 POLAZAK'],
                                'povratak' => ['bg' => '#E0E7FF', 'text' => '#3730A3', 'border' => '#6366F1', 'label' => '↩️ POVRATAK'],
                                'formiran' => ['bg' => '#FEF3C7', 'text' => '#92400E', 'border' => '#F59E0B', 'label' => '🆕 FORMIRAN'],
                                'intervencija_zavrsena' => ['bg' => '#D1FAE5', 'text' => '#065F46', 'border' => '#10B981', 'label' => '✅ ZAVRŠILI'],
                                default => ['bg' => '#F1F5F9', 'text' => '#475569', 'border' => '#94A3B8', 'label' => strtoupper(str_replace('_', ' ', $tim->trenutni_status))],
                            };
                        @endphp
                        <div style="background: white; border: 1px solid #E2E8F0; border-left: 3px solid {{ $statusBoja['border'] }}; border-radius: 6px; padding: 7px 9px; margin-bottom: 5px;">
                            <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
                                <span style="font-size: 9px; font-weight: 800; background: {{ $statusBoja['bg'] }}; color: {{ $statusBoja['text'] }}; padding: 2px 5px; border-radius: 3px;">{{ $statusBoja['label'] }}</span>
                                <span style="font-size: 12px; font-weight: 800; color: #0F172A;">{{ $tim->naziv }}</span>
                                <span style="margin-left: auto; font-size: 10px; color: #64748B; font-weight: 700;">🧑‍🚒 {{ $tim->trenutniClanovi->count() }}</span>
                            </div>
                            <div style="font-size: 10px; color: #64748B; margin-bottom: 5px;">
                                🏠 {{ $tim->bazaPostrojba?->skraceni_naziv ?? $tim->bazaPostrojba?->naziv ?? '—' }}
                                • 👑 {{ $tim->zapovjednik?->puno_ime ?? '—' }}
                            </div>
                            
                            @if($tim->trenutniClanovi->isNotEmpty())
                                <div style="font-size: 9px; color: #64748B; margin-bottom: 5px; line-height: 1.3;">
                                    @foreach($tim->trenutniClanovi->take(5) as $clan)
                                        <span style="background: #F8FAFC; padding: 1px 5px; border-radius: 3px; margin-right: 2px; display: inline-block; margin-bottom: 2px;">
                                            {{ $clan->vatrogasac?->prezime ?? '?' }} {{ substr($clan->vatrogasac?->ime ?? '', 0, 1) }}.
                                        </span>
                                    @endforeach
                                    @if($tim->trenutniClanovi->count() > 5)
                                        <span style="font-size: 9px; color: #94A3B8; font-style: italic;">+{{ $tim->trenutniClanovi->count() - 5 }} više</span>
                                    @endif
                                </div>
                            @endif
                            
                            <div style="display: flex; gap: 3px; flex-wrap: wrap;">
                                @if(in_array($tim->trenutni_status, ['formiran', 'cekanje_u_bazi', 'odmor']))
                                    <button wire:click="timPolazak({{ $tim->id }})" style="background: #3B82F6; color: white; border: none; padding: 3px 7px; border-radius: 4px; font-size: 9px; font-weight: 700; cursor: pointer;">🚒 Polazak</button>
                                @endif
                                @if($tim->trenutni_status === 'polazak')
                                    <button wire:click="timNaMjestu({{ $tim->id }})" style="background: #DC2626; color: white; border: none; padding: 3px 7px; border-radius: 4px; font-size: 9px; font-weight: 700; cursor: pointer;">📍 Na mjestu</button>
                                @endif
                                @if($tim->trenutni_status === 'na_mjestu')
                                    <button wire:click="timZavrsili({{ $tim->id }})" style="background: #10B981; color: white; border: none; padding: 3px 7px; border-radius: 4px; font-size: 9px; font-weight: 700; cursor: pointer;">✅ Završili</button>
                                @endif
                                @if(in_array($tim->trenutni_status, ['intervencija_zavrsena', 'na_mjestu']))
                                    <button wire:click="timPovratak({{ $tim->id }})" style="background: #6366F1; color: white; border: none; padding: 3px 7px; border-radius: 4px; font-size: 9px; font-weight: 700; cursor: pointer;">↩️ Povratak</button>
                                @endif
                                <a href="/admin/tims/{{ $tim->id }}/edit" target="_blank" style="background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; padding: 3px 7px; border-radius: 4px; font-size: 9px; font-weight: 700; text-decoration: none; margin-left: auto;">⚙</a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div style="background: #FEF9C3; border: 1px solid #FACC15; border-radius: 6px; padding: 10px; display: flex; align-items: center; gap: 6px;">
                        <span style="font-size: 14px;">⚠</span>
                        <div>
                            <div style="font-size: 11px; font-weight: 800; color: #854D0E;">Bez angažiranih timova</div>
                            <div style="font-size: 9px; color: #92400E;">Klikni "Novi tim" ili "Pošalji"</div>
                        </div>
                    </div>
                @endif
            </div>
            
            <div style="padding: 6px 10px; background: #F8FAFC; border-top: 1px solid #E2E8F0; flex-shrink: 0; display: flex; gap: 4px;">
                <a href="/admin/intervencijas/{{ $intervencija->id }}/edit"
                   style="flex: 1; background: white; border: 1px solid #E2E8F0; color: #475569; padding: 6px; border-radius: 5px; text-decoration: none; font-size: 10px; font-weight: 700; text-align: center;">
                    ⚙ Puno upravljanje
                </a>
                <button wire:click="zatvoriIntervenciju"
                        wire:confirm="Sigurno zatvoriti intervenciju?"
                        style="background: white; border: 1px solid #FCA5A5; color: #DC2626; padding: 6px 12px; border-radius: 5px; font-size: 10px; font-weight: 700; cursor: pointer;">
                    🔒 Zatvori
                </button>
            </div>
        </div>
    </div>
</div>
