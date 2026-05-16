<x-filament-panels::page>
    <div wire:ignore.self>
        <style>
            body, html { overflow: hidden !important; }
            
            aside.fi-sidebar,
            .fi-sidebar-open .fi-sidebar,
            .fi-sidebar { display: none !important; }
            .fi-layout { padding-left: 0 !important; }
            .fi-topbar { left: 0 !important; }
            .fi-main-ctn { margin-left: 0 !important; }
            
            .fi-main { padding: 0 !important; max-width: 100% !important; }
            section.fi-main { padding: 0 !important; }
            .fi-page { padding: 0 !important; }
            .fi-page-header-main-ctn { padding: 8px 16px !important; }
            
            .fo-scroll::-webkit-scrollbar { width: 6px; }
            .fo-scroll::-webkit-scrollbar-track { background: transparent; }
            .fo-scroll::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
            .fo-scroll::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
            
            .fo-row { transition: background 0.12s, border-color 0.12s; cursor: pointer; }
            .fo-row:hover { background: #F1F5F9 !important; }
            .fo-row.active { background: #EFF6FF !important; border-left-width: 4px !important; }
            
            .fo-pulse { animation: foPulse 2s ease-in-out infinite; }
            @keyframes foPulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.55; }
            }

            .fo-input {
                background: white;
                border: 1px solid #E2E8F0;
                border-radius: 8px;
                padding: 6px 12px;
                font-size: 12px;
                color: #0F172A;
                width: 100%;
                font-family: inherit;
                outline: none;
                transition: border-color 0.15s;
            }
            .fo-input:focus { border-color: #3B82F6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }

            .fo-filter-btn {
                background: white;
                border: 1px solid #E2E8F0;
                color: #475569;
                padding: 5px 10px;
                border-radius: 6px;
                font-size: 11px;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.12s;
                user-select: none;
                white-space: nowrap;
            }
            .fo-filter-btn:hover { background: #F8FAFC; }
            .fo-filter-btn.active { background: #0F172A; color: white; border-color: #0F172A; }
            
            .fo-tab-btn {
                flex: 1;
                background: transparent;
                border: none;
                color: #64748B;
                padding: 8px 0;
                font-size: 12px;
                font-weight: 700;
                cursor: pointer;
                border-bottom: 2px solid transparent;
                transition: all 0.15s;
            }
            .fo-tab-btn.active { color: #0F172A; border-bottom-color: #0F172A; }
            .fo-tab-btn:hover:not(.active) { color: #0F172A; background: #F8FAFC; }
        </style>

        <div style="position: fixed; top: 64px; left: 0; right: 0; bottom: 0; background: #F1F5F9; display: flex; flex-direction: column;">
            
            <div style="background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%); padding: 10px 18px; flex-shrink: 0; color: white; box-shadow: 0 2px 12px rgba(0,0,0,0.15);">
                <div style="display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap;">
                    
                    <div style="display: flex; align-items: center; gap: 12px;">
                        @php
                            $stanjeBg = match($stanje['boja']) {
                                '#DC2626' => 'linear-gradient(135deg, #EF4444 0%, #991B1B 100%)',
                                '#F97316' => 'linear-gradient(135deg, #F97316 0%, #C2410C 100%)',
                                default => 'linear-gradient(135deg, #10B981 0%, #047857 100%)',
                            };
                        @endphp
                        <div style="background: {{ $stanjeBg }}; padding: 6px 12px; border-radius: 7px; box-shadow: 0 4px 12px rgba(0,0,0,0.25);">
                            <div style="font-size: 8px; font-weight: 700; opacity: 0.9; text-transform: uppercase; letter-spacing: 1.5px;">Stanje</div>
                            <div style="font-size: 13px; font-weight: 900; letter-spacing: -0.3px; display: flex; align-items: center; gap: 6px; margin-top: 1px;">
                                <span class="fo-pulse" style="display: inline-block; width: 6px; height: 6px; background: white; border-radius: 50%;"></span>
                                {{ $stanje['naslov'] }}
                            </div>
                        </div>

                        <div style="display: flex; gap: 3px;">
                            <div style="background: rgba(255,255,255,0.08); padding: 5px 10px; border-radius: 6px; min-width: 64px; text-align: center; border: 1px solid rgba(255,255,255,0.1);">
                                <div style="font-size: 16px; font-weight: 900; line-height: 1; color: #FCA5A5;">{{ $brojDojava }}</div>
                                <div style="font-size: 8px; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.8px; margin-top: 2px; font-weight: 600;">Dojava</div>
                            </div>
                            <div style="background: rgba(255,255,255,0.08); padding: 5px 10px; border-radius: 6px; min-width: 64px; text-align: center; border: 1px solid rgba(255,255,255,0.1);">
                                <div style="font-size: 16px; font-weight: 900; line-height: 1; color: #FDBA74;">{{ $brojIntervencija }}</div>
                                <div style="font-size: 8px; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.8px; margin-top: 2px; font-weight: 600;">Inter.</div>
                            </div>
                            <div style="background: rgba(255,255,255,0.08); padding: 5px 10px; border-radius: 6px; min-width: 64px; text-align: center; border: 1px solid rgba(255,255,255,0.1);">
                                <div style="font-size: 16px; font-weight: 900; line-height: 1; color: #93C5FD;">{{ $brojTimova }}</div>
                                <div style="font-size: 8px; opacity: 0.85; text-transform: uppercase; letter-spacing: 0.8px; margin-top: 2px; font-weight: 600;">Timova</div>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 8px; align-items: center;">
                        <div style="text-align: right;">
                            <div style="font-size: 8px; opacity: 0.7; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Vrijeme</div>
                            <div id="fireops-clock" style="font-size: 16px; font-weight: 900; line-height: 1; font-variant-numeric: tabular-nums; letter-spacing: -0.3px;">--:--:--</div>
                        </div>
                        
                        <div style="width: 1px; height: 28px; background: rgba(255,255,255,0.15);"></div>
                        
                        <a href="/admin/operativa" target="_blank"
                           style="background: rgba(59, 130, 246, 0.9); color: white; padding: 7px 12px; border-radius: 7px; text-decoration: none; font-size: 11px; font-weight: 800; display: flex; align-items: center; gap: 6px;">
                            🗺 Operativna karta
                        </a>
                        
                        <button wire:click="otvoriNovuDojavu"
                                style="background: linear-gradient(135deg, #EF4444 0%, #B91C1C 100%); color: white; padding: 7px 12px; border: none; border-radius: 7px; font-size: 11px; font-weight: 800; display: flex; align-items: center; gap: 6px; cursor: pointer;">
                            ➕ Nova dojava
                        </button>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 420px 1fr 320px; gap: 8px; padding: 8px; flex: 1; min-height: 0; overflow: hidden;">
                
                <div style="background: white; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); border: 1px solid #E2E8F0; display: flex; flex-direction: column; min-height: 0; overflow: hidden;">
                    
                    <div style="display: flex; border-bottom: 1px solid #E2E8F0; flex-shrink: 0; background: #F8FAFC;">
                        <button wire:click="postaviTab('sve')" class="fo-tab-btn {{ $aktivniTab === 'sve' ? 'active' : '' }}">
                            📋 SVE ({{ $brojDojava + $brojIntervencija }})
                        </button>
                        <button wire:click="postaviTab('dojave')" class="fo-tab-btn {{ $aktivniTab === 'dojave' ? 'active' : '' }}">
                            📞 Dojave ({{ $brojDojava }})
                        </button>
                        <button wire:click="postaviTab('intervencije')" class="fo-tab-btn {{ $aktivniTab === 'intervencije' ? 'active' : '' }}">
                            🔥 Intervencije ({{ $brojIntervencija }})
                        </button>
                    </div>
                    
                    <div style="padding: 10px 12px; border-bottom: 1px solid #E2E8F0; flex-shrink: 0;">
                        <input type="text" 
                               wire:model.live.debounce.300ms="pretraga"
                               placeholder="🔍 Traži po adresi, broju, JLS..." 
                               class="fo-input" 
                               style="font-size: 12px; margin-bottom: 8px;">
                        <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                            <button wire:click="postaviPrio('sve')" class="fo-filter-btn {{ $aktivniPrio === 'sve' ? 'active' : '' }}">SVE</button>
                            <button wire:click="postaviPrio('kriticna')" class="fo-filter-btn {{ $aktivniPrio === 'kriticna' ? 'active' : '' }}">🔴 KRIT</button>
                            <button wire:click="postaviPrio('visoka')" class="fo-filter-btn {{ $aktivniPrio === 'visoka' ? 'active' : '' }}">🟡 VIS</button>
                            <button wire:click="postaviPrio('standardna')" class="fo-filter-btn {{ $aktivniPrio === 'standardna' ? 'active' : '' }}">🟢 STD</button>
                        </div>
                    </div>
                    
                    <div class="fo-scroll" id="master-list" style="overflow-y: auto; flex: 1;">
                        @php
                            $sviItems = collect();
                            if ($aktivniTab === 'sve' || $aktivniTab === 'intervencije') {
                                foreach($intervencije as $int) {
                                    $sviItems->push(['tip' => 'intervencija', 'data' => $int, 'prioritet' => $int->prioritet, 'vrijeme' => $int->vrijeme_otvaranja]);
                                }
                            }
                            if ($aktivniTab === 'sve' || $aktivniTab === 'dojave') {
                                foreach($dojave as $d) {
                                    $sviItems->push(['tip' => 'dojava', 'data' => $d, 'prioritet' => $d->prioritet, 'vrijeme' => $d->vrijeme_zaprimanja]);
                                }
                            }
                            $sviItems = $sviItems->sortBy([
                                ['prioritet', 'asc'],
                                ['vrijeme', 'desc']
                            ])->values();
                        @endphp
                        
                        @forelse($sviItems as $item)
                            @php
                                $tip = $item['tip'];
                                $data = $item['data'];
                                $aktivan = ($this->odabrano === "{$tip}:{$data->id}");
                                $prio = match($data->prioritet) {
                                    'kriticna' => ['border' => '#DC2626', 'badge' => 'KRIT', 'badgeBg' => '#DC2626'],
                                    'visoka' => ['border' => '#F59E0B', 'badge' => 'VIS', 'badgeBg' => '#F59E0B'],
                                    default => ['border' => '#10B981', 'badge' => 'STD', 'badgeBg' => '#10B981'],
                                };
                            @endphp
                            
                            @if($tip === 'dojava')
                                @php
                                    $tipIkona = match($data->tip_nepogode) {
                                        'olujno_nevrijeme' => '⛈', 'poplava' => '🌊', 'pozar' => '🔥',
                                        'snijeg_led' => '❄', 'klizište' => '⛰', 'tuca' => '🧊',
                                        'potres' => '🌍', 'spasavanje' => '⛑', 'opasne_tvari' => '☣',
                                        default => '⚠',
                                    };
                                @endphp
                                <div wire:click="odaberi('dojava', {{ $data->id }})"
                                     wire:key="d-{{ $data->id }}"
                                     class="fo-row {{ $aktivan ? 'active' : '' }}"
                                     style="display: flex; align-items: center; gap: 8px; padding: 9px 12px; border-bottom: 1px solid #F1F5F9; border-left: 3px solid {{ $prio['border'] }};">
                                    
                                    <span style="font-size: 14px; width: 18px; text-align: center; flex-shrink: 0;">{{ $tipIkona }}</span>
                                    <span style="font-size: 8px; font-weight: 800; background: {{ $prio['badgeBg'] }}; color: white; padding: 2px 5px; border-radius: 3px; flex-shrink: 0;">{{ $prio['badge'] }}</span>
                                    <span style="font-size: 8px; font-weight: 800; background: #DBEAFE; color: #1E40AF; padding: 2px 5px; border-radius: 3px; flex-shrink: 0;">📞</span>
                                    
                                    <div style="flex: 1; min-width: 0;">
                                        <div style="font-size: 12px; font-weight: 700; color: #0F172A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $data->adresa }}
                                        </div>
                                        <div style="font-size: 10px; color: #94A3B8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            #{{ $data->broj_dojave }} • {{ $data->jls?->naziv ?? '—' }}
                                        </div>
                                    </div>
                                    
                                    @if($data->intervencija)
                                        <span style="font-size: 8px; font-weight: 800; background: #FEE2E2; color: #991B1B; padding: 2px 5px; border-radius: 3px; flex-shrink: 0;" title="Vezana">🔥</span>
                                    @endif
                                    
                                    <span style="font-size: 10px; color: #64748B; font-weight: 700; font-variant-numeric: tabular-nums; flex-shrink: 0;">{{ $data->vrijeme_zaprimanja->format('H:i') }}</span>
                                </div>
                            
                            @else
                                @php
                                    $aktivniTimovi = $data->timovi->where('trenutni_status', '!=', 'raspusten');
                                    $brojLjudi = $aktivniTimovi->sum(fn($t) => $t->trenutniClanovi->count());
                                @endphp
                                <div wire:click="odaberi('intervencija', {{ $data->id }})"
                                     wire:key="i-{{ $data->id }}"
                                     class="fo-row {{ $aktivan ? 'active' : '' }}"
                                     style="display: flex; align-items: center; gap: 8px; padding: 9px 12px; border-bottom: 1px solid #F1F5F9; border-left: 3px solid {{ $prio['border'] }};">
                                    
                                    <span style="font-size: 8px; font-weight: 800; background: {{ $prio['badgeBg'] }}; color: white; padding: 2px 5px; border-radius: 3px; flex-shrink: 0;">{{ $prio['badge'] }}</span>
                                    <span style="font-size: 8px; font-weight: 800; background: #FEE2E2; color: #991B1B; padding: 2px 5px; border-radius: 3px; flex-shrink: 0;">🔥</span>
                                    
                                    <div style="flex: 1; min-width: 0;">
                                        <div style="font-size: 12px; font-weight: 800; color: #0F172A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $data->naziv }}
                                        </div>
                                        <div style="font-size: 10px; color: #94A3B8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            #{{ $data->broj }} • {{ $data->jls?->naziv ?? '—' }} • 🚒 {{ $aktivniTimovi->count() }} · 👥 {{ $brojLjudi }}
                                        </div>
                                    </div>
                                    
                                    <span style="font-size: 10px; color: #64748B; font-weight: 700; font-variant-numeric: tabular-nums; flex-shrink: 0;">{{ $data->vrijeme_otvaranja->format('H:i') }}</span>
                                </div>
                            @endif
                        @empty
                            <div style="text-align: center; padding: 50px 16px; color: #94A3B8;">
                                <div style="font-size: 36px; margin-bottom: 10px;">✓</div>
                                <div style="font-size: 12px; font-weight: 700; color: #475569;">Bez aktivnosti</div>
                                <div style="font-size: 11px; margin-top: 4px;">Sustav je u pripravnosti</div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div style="background: white; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); border: 1px solid #E2E8F0; display: flex; flex-direction: column; min-height: 0; overflow: hidden;"
                     wire:key="detalji-{{ $odabrano ?? 'prazno' }}">
                    
                    @if($odabranaDojava)
                        @include('filament.admin.pages._dispatch-dojava-detail', ['dojava' => $odabranaDojava])
                    @elseif($odabranaIntervencija)
                        @include('filament.admin.pages._dispatch-intervencija-detail', ['intervencija' => $odabranaIntervencija])
                    @else
                        <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; text-align: center;">
                            <div style="font-size: 72px; margin-bottom: 16px; opacity: 0.4;">📋</div>
                            <div style="font-size: 18px; font-weight: 800; color: #475569; margin-bottom: 6px;">Odaberi stavku lijevo</div>
                            <div style="font-size: 13px; color: #94A3B8; max-width: 320px; line-height: 1.5;">
                                Klikni na bilo koju dojavu ili intervenciju iz popisa za prikaz detalja i akcija.
                            </div>
                            <div style="margin-top: 32px; display: flex; gap: 8px;">
                                <button wire:click="otvoriNovuDojavu"
                                        style="background: linear-gradient(135deg, #EF4444 0%, #B91C1C 100%); color: white; padding: 10px 18px; border: none; border-radius: 8px; font-size: 13px; font-weight: 800; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); cursor: pointer;">
                                    ➕ Otvori novu dojavu
                                </button>
                                <a href="/admin/operativa" target="_blank"
                                   style="background: white; color: #475569; border: 1px solid #E2E8F0; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 800;">
                                    🗺 Operativna karta
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <div style="background: white; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.04); border: 1px solid #E2E8F0; display: flex; flex-direction: column; min-height: 0; overflow: hidden;">
                    
                    <div style="padding: 10px 12px; border-bottom: 1px solid #E2E8F0; flex-shrink: 0;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 16px;">📋</span>
                            <span style="font-size: 13px; font-weight: 800; color: #0F172A;">Timeline</span>
                            <span style="background: #DBEAFE; color: #1E40AF; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 800;">{{ $timeline->count() }}</span>
                            <span style="margin-left: auto; font-size: 10px; color: #64748B; display: flex; align-items: center; gap: 4px; font-weight: 600;">
                                <span class="fo-pulse" style="display: inline-block; width: 6px; height: 6px; background: #10B981; border-radius: 50%;"></span>
                                LIVE
                            </span>
                        </div>
                    </div>
                    
                    <div class="fo-scroll" style="overflow-y: auto; flex: 1;">
                        @forelse($timeline as $log)
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
                                    'formiran' => '#F59E0B',
                                    default => '#94A3B8',
                                };
                            @endphp
                            <div style="display: flex; align-items: center; gap: 8px; padding: 7px 12px; border-bottom: 1px solid #F1F5F9; border-left: 3px solid {{ $boja }};">
                                <span style="font-size: 13px; flex-shrink: 0;">{{ $tipIkona }}</span>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-size: 8px; font-weight: 800; color: {{ $boja }}; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.2;">{{ str_replace('_', ' ', $log->status) }}</div>
                                    <div style="font-size: 11px; color: #0F172A; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $log->tim?->naziv ?? '—' }}</div>
                                </div>
                                <span style="font-size: 10px; color: #64748B; font-weight: 700; font-variant-numeric: tabular-nums; flex-shrink: 0;">{{ $log->vrijeme->format('H:i') }}</span>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 50px 16px; color: #94A3B8;">
                                <div style="font-size: 32px; margin-bottom: 10px;">📜</div>
                                <div style="font-size: 11px; font-weight: 600;">Bez aktivnosti</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        @if($aktivniModal === 'noviTim')
            @include('filament.admin.pages._dispatch-modal-novi-tim')
        @endif
        @if($aktivniModal === 'posaljiTim')
            @include('filament.admin.pages._dispatch-modal-posalji-tim')
        @endif
        @if($aktivniModal === 'rezervirajTim')
            @include('filament.admin.pages._dispatch-modal-rezerviraj-tim')
        @endif
        @if($aktivniModal === 'dodajDojavu')
            @include('filament.admin.pages._dispatch-modal-dodaj-dojavu')
        @endif
        @if($aktivniModal === 'upravljajTimom')
            @include('filament.admin.pages._dispatch-modal-upravljaj-timom', ['upravljaniTim' => $upravljaniTim])
        @endif
        @if($aktivniModal === 'novaDojava' || $aktivniModal === 'urediDojavu')
            @include('filament.admin.pages._dispatch-modal-nova-dojava')
        @endif
    </div>

    @push('scripts')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            (function() {
                const upd = () => {
                    const el = document.getElementById('fireops-clock');
                    if (el) el.textContent = new Date().toLocaleTimeString('hr-HR');
                };
                upd();
                setInterval(upd, 1000);

                document.addEventListener('livewire:initialized', () => {
                    if (window.Livewire) {
                        window.Livewire.on('osvjeziMapu', () => {
                            const payload = { timestamp: Date.now() };
                            localStorage.setItem('fireops:refresh-mapa', JSON.stringify(payload));
                        });
                    }
                });

                window.fireopsZoomirajNaMapi = function(lat, lng, naslov, tip) {
                    if (!lat || !lng) return;
                    const payload = { lat: parseFloat(lat), lng: parseFloat(lng), naslov, tip, timestamp: Date.now() };
                    localStorage.setItem('fireops:zoom-mapa', JSON.stringify(payload));
                    window.dispatchEvent(new StorageEvent('storage', { key: 'fireops:zoom-mapa', newValue: JSON.stringify(payload) }));
                };

                window._fireopsMape = window._fireopsMape || {};
                
                const initMiniMapa = () => {
                    if (typeof L === 'undefined') return;
                    
                    const trenutnoUDomu = new Set();
                    document.querySelectorAll('[id^="fo-mini-mapa-"]').forEach(el => {
                        trenutnoUDomu.add(el.id);
                    });
                    
                    Object.keys(window._fireopsMape).forEach(id => {
                        if (!trenutnoUDomu.has(id)) {
                            try {
                                window._fireopsMape[id].remove();
                            } catch(e) {}
                            delete window._fireopsMape[id];
                        }
                    });
                    
                    document.querySelectorAll('[id^="fo-mini-mapa-"]').forEach(el => {
                        if (window._fireopsMape[el.id]) return;
                        
                        const lat = parseFloat(el.dataset.intLat);
                        const lng = parseFloat(el.dataset.intLng);
                        const naziv = el.dataset.intNaziv || '';
                        let timovi = [];
                        try {
                            timovi = JSON.parse(el.dataset.timovi || '[]');
                        } catch(e) {}
                        
                        if (!lat || !lng) {
                            el.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100%;color:white;font-size:11px;font-weight:600;padding:10px;text-align:center;">📍 Bez GPS koordinata</div>';
                            return;
                        }
                        
                        el.innerHTML = '';
                        el._leaflet_id = undefined;
                        
                        const mapa = L.map(el, { zoomControl: true, attributionControl: false }).setView([lat, lng], 15);
                        window._fireopsMape[el.id] = mapa;
                        
                        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                            maxZoom: 19,
                        }).addTo(mapa);
                        
                        const intIcon = L.divIcon({
                            className: 'fo-int-pin',
                            html: '<div style="width:20px;height:20px;background:#DC2626;border:3px solid white;border-radius:4px;box-shadow:0 0 0 2px #DC2626, 0 2px 8px rgba(0,0,0,0.6);"></div>',
                            iconSize: [26, 26],
                            iconAnchor: [13, 13],
                        });
                        L.marker([lat, lng], { icon: intIcon }).addTo(mapa).bindPopup('<b>🔥 ' + naziv + '</b>');
                        
                        timovi.forEach((t, idx) => {
                            let timLat, timLng;
                            if (t.status === 'polazak') {
                                timLat = t.baza_lat ? parseFloat(t.baza_lat) : lat;
                                timLng = t.baza_lng ? parseFloat(t.baza_lng) : lng;
                            } else if (t.status === 'na_mjestu') {
                                const offset = (idx % 8) * 0.00008;
                                timLat = lat + offset;
                                timLng = lng + offset;
                            } else if (t.status === 'povratak') {
                                timLat = t.baza_lat ? (parseFloat(t.baza_lat) + lat) / 2 : lat;
                                timLng = t.baza_lng ? (parseFloat(t.baza_lng) + lng) / 2 : lng;
                            } else {
                                return;
                            }
                            
                            if (!timLat || !timLng) return;
                            
                            const timIcon = L.divIcon({
                                className: 'fo-tim-pin',
                                html: '<div style="font-size:20px;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.5));">🚒</div>',
                                iconSize: [24, 24],
                                iconAnchor: [12, 12],
                            });
                            L.marker([timLat, timLng], { icon: timIcon })
                                .addTo(mapa)
                                .bindPopup('<b>' + t.naziv + '</b><br><small>' + (t.status || '').replace(/_/g, ' ').toUpperCase() + '</small>');
                        });
                    });
                };
                
                document.addEventListener('livewire:initialized', initMiniMapa);
                document.addEventListener('livewire:updated', initMiniMapa);
                document.addEventListener('livewire:navigated', initMiniMapa);
                
                setTimeout(initMiniMapa, 500);
                setInterval(initMiniMapa, 2000);
            })();
        </script>
    @endpush
</x-filament-panels::page>
