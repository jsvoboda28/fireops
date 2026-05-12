<x-filament-panels::page>
    <div wire:ignore.self>
        <div style="display: grid; grid-template-columns: 320px 1fr 360px; gap: 16px; height: calc(100vh - 200px); min-height: 600px;">

            {{-- ===== LIJEVO ===== --}}
            <div style="display: flex; flex-direction: column; gap: 12px; overflow: hidden;">
                
                {{-- Aktivne dojave --}}
                <div style="background: white; border-radius: 12px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; display: flex; flex-direction: column; flex: 1; min-height: 0;">
                    <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 12px; border-bottom: 2px solid #F3F4F6; margin-bottom: 12px; flex-shrink: 0;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 14px; font-weight: 800; color: #111827;">📞 Aktivne dojave</span>
                            <span style="font-size: 11px; font-weight: 700; background: #FEE2E2; color: #991B1B; padding: 2px 8px; border-radius: 10px;">
                                {{ $brojDojava }}
                            </span>
                        </div>
                    </div>
                    
                    <div style="overflow-y: auto; flex: 1; display: flex; flex-direction: column; gap: 6px;" id="fireops-dojave-list">
                        @forelse($dojave as $dojava)
                            @php
                                $prio = match($dojava->prioritet) {
                                    'kriticna' => ['bg' => '#FEE2E2', 'border' => '#DC2626', 'text' => '#991B1B', 'badge' => 'KRIT.'],
                                    'visoka' => ['bg' => '#FEF3C7', 'border' => '#F59E0B', 'text' => '#92400E', 'badge' => 'VIS.'],
                                    default => ['bg' => '#D1FAE5', 'border' => '#10B981', 'text' => '#065F46', 'badge' => 'STD.'],
                                };
                                $tip = match($dojava->tip_nepogode) {
                                    'olujno_nevrijeme' => '⛈',
                                    'poplava' => '🌊',
                                    'pozar' => '🔥',
                                    'snijeg_led' => '❄',
                                    'klizište' => '⛰',
                                    'tuca' => '🧊',
                                    'potres' => '🌍',
                                    default => '❓',
                                };
                            @endphp
                            <div class="fireops-dojava-card"
                                 data-dojava-id="{{ $dojava->id }}"
                                 data-lat="{{ $dojava->latitude }}"
                                 data-lng="{{ $dojava->longitude }}"
                                 style="cursor: pointer; background: {{ $prio['bg'] }}; border-left: 4px solid {{ $prio['border'] }}; border-radius: 6px; padding: 10px 12px; transition: transform 0.15s, box-shadow 0.15s;"
                                 onmouseover="this.style.transform='translateX(3px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';"
                                 onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='none';">
                                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
                                    <span style="font-size: 16px;">{{ $tip }}</span>
                                    <span style="font-size: 10px; font-weight: 800; background: {{ $prio['border'] }}; color: white; padding: 2px 6px; border-radius: 3px;">
                                        {{ $prio['badge'] }}
                                    </span>
                                    <span style="font-size: 10px; color: {{ $prio['text'] }}; font-weight: 700; margin-left: auto;">
                                        {{ $dojava->vrijeme_zaprimanja->format('H:i') }}
                                    </span>
                                </div>
                                <div style="font-size: 12px; font-weight: 700; color: #111827; line-height: 1.3;">
                                    {{ \Illuminate\Support\Str::limit($dojava->adresa, 40) }}
                                </div>
                                <div style="font-size: 11px; color: #6B7280; margin-top: 2px;">
                                    {{ $dojava->jls?->naziv ?? '—' }} • {{ $dojava->vrijeme_zaprimanja->diffForHumans(null, true, true) }}
                                </div>
                            </div>
                        @empty
                            <div style="text-align: center; padding: 30px 10px; color: #6B7280;">
                                <div style="font-size: 32px; margin-bottom: 6px;">✓</div>
                                <div style="font-size: 12px; font-weight: 600;">Nema aktivnih dojava</div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Aktivni događaji --}}
                <div style="background: white; border-radius: 12px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; display: flex; flex-direction: column; max-height: 280px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 12px; border-bottom: 2px solid #F3F4F6; margin-bottom: 12px; flex-shrink: 0;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 14px; font-weight: 800; color: #111827;">🔥 Operativni događaji</span>
                            <span style="font-size: 11px; font-weight: 700; background: #FEE2E2; color: #991B1B; padding: 2px 8px; border-radius: 10px;">
                                {{ $brojDogadjaja }}
                            </span>
                        </div>
                    </div>
                    
                    <div style="overflow-y: auto; flex: 1; display: flex; flex-direction: column; gap: 6px;">
                        @forelse($dogadjaji as $d)
                            @php
                                $status = match($d->status) {
                                    'aktivan' => ['bg' => '#FEE2E2', 'border' => '#DC2626', 'text' => '#991B1B'],
                                    'pracenje' => ['bg' => '#DBEAFE', 'border' => '#3B82F6', 'text' => '#1E40AF'],
                                    default => ['bg' => '#F3F4F6', 'border' => '#6B7280', 'text' => '#374151'],
                                };
                            @endphp
                            <a href="{{ route('filament.admin.resources.operativni-dogadjajs.edit', $d) }}"
                               style="text-decoration: none; color: inherit; display: block; background: {{ $status['bg'] }}; border-left: 4px solid {{ $status['border'] }}; border-radius: 6px; padding: 10px 12px;">
                                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
                                    <span style="font-size: 9px; font-weight: 800; background: {{ $status['border'] }}; color: white; padding: 2px 5px; border-radius: 3px; text-transform: uppercase;">
                                        {{ $d->status }}
                                    </span>
                                    @if($d->stupanj_sukoba)
                                        <span style="font-size: 9px; font-weight: 800; background: #F59E0B; color: white; padding: 2px 5px; border-radius: 3px;">
                                            STUPANJ {{ $d->stupanj_sukoba }}
                                        </span>
                                    @endif
                                    <span style="font-size: 10px; color: {{ $status['text'] }}; font-weight: 700; margin-left: auto;">
                                        {{ $d->dojave_count }} dojava
                                    </span>
                                </div>
                                <div style="font-size: 13px; font-weight: 700; color: #111827; line-height: 1.3;">
                                    {{ \Illuminate\Support\Str::limit($d->naziv, 45) }}
                                </div>
                                <div style="font-size: 11px; color: #6B7280; margin-top: 2px;">
                                    {{ $d->jls?->naziv ?? 'Cijela županija' }}
                                </div>
                            </a>
                        @empty
                            <div style="text-align: center; padding: 20px 10px; color: #6B7280;">
                                <div style="font-size: 12px;">Bez aktivnih događaja</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ===== SREDINA: LEAFLET MAPA ===== --}}
            <div style="border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; flex-direction: column; overflow: hidden; position: relative; background: #1E40AF;">
                
                <div style="position: absolute; top: 0; left: 0; right: 0; padding: 12px 18px; background: linear-gradient(to bottom, rgba(0,0,0,0.7), transparent); color: white; z-index: 1000; pointer-events: none;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.9;">FireOps PSŽ • Operativna karta</div>
                            <div style="font-size: 16px; font-weight: 800; margin-top: 2px;">
                                <span style="opacity: 0.8;">{{ $brojPostrojbi }}</span> postrojbi · 
                                <span style="opacity: 0.8;">{{ $brojDojava }}</span> dojava
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 10px; opacity: 0.9; text-transform: uppercase; letter-spacing: 1px;">Stanje</div>
                            <div style="font-size: 14px; font-weight: 800; color: {{ $stanje['boja'] === '#DC2626' ? '#FCA5A5' : ($stanje['boja'] === '#F97316' ? '#FED7AA' : '#86EFAC') }};">
                                {{ $stanje['naslov'] }}
                            </div>
                        </div>
                    </div>
                </div>

                <div style="position: absolute; bottom: 16px; left: 16px; background: rgba(255,255,255,0.95); padding: 10px 14px; border-radius: 8px; z-index: 1000; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                    <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; margin-bottom: 8px;">LEGENDA</div>
                    <div style="display: flex; flex-direction: column; gap: 4px; font-size: 11px; color: #111827;">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span style="width: 12px; height: 12px; border-radius: 50%; background: #10B981; border: 2px solid white; box-shadow: 0 0 0 1px #10B981;"></span> DVD
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span style="width: 12px; height: 12px; border-radius: 50%; background: #3B82F6; border: 2px solid white; box-shadow: 0 0 0 1px #3B82F6;"></span> JVP
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span style="width: 12px; height: 12px; border-radius: 50%; background: #F59E0B; border: 2px solid white; box-shadow: 0 0 0 1px #F59E0B;"></span> IDVD
                        </div>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span style="width: 12px; height: 12px; border-radius: 50%; background: #DC2626; border: 2px solid white; box-shadow: 0 0 0 1px #DC2626;"></span> Aktivna dojava
                        </div>
                    </div>
                </div>
                
                <div id="fireops-map" 
                     data-mapa="{{ $mapaData }}"
                     style="width: 100%; height: 100%; min-height: 600px;"></div>
            </div>

            {{-- ===== DESNO: DETALJI ===== --}}
            <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; display: flex; flex-direction: column; overflow: hidden;">
                <div id="fireops-detalji" style="flex: 1; display: flex; flex-direction: column; overflow-y: auto;">
                    {{-- Default prazno stanje --}}
                    <div style="padding: 16px 16px 12px 16px; border-bottom: 2px solid #F3F4F6;">
                        <div style="font-size: 14px; font-weight: 800; color: #111827;">📋 Detalji</div>
                        <div style="font-size: 11px; color: #6B7280; margin-top: 2px;">Odaberite dojavu ili postrojbu</div>
                    </div>
                    <div style="flex: 1; display: flex; align-items: center; justify-content: center; color: #9CA3AF; padding: 20px;">
                        <div style="text-align: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="48" height="48" style="margin: 0 auto; opacity: 0.4;">
                                <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" clip-rule="evenodd" />
                            </svg>
                            <div style="font-size: 13px; margin-top: 12px; max-width: 220px;">Klikni na pin na mapi ili karticu lijevo za detalje i akcije</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                if (window._fireopsMapInit) return;
                window._fireopsMapInit = true;

                // Globalni state
                window.FireOps = {
                    map: null,
                    markers: { postrojbe: {}, dojave: {} },
                    data: null,
                    selectedMarker: null,
                };

                const loadLeafletCss = () => {
                    if (document.getElementById('leaflet-css')) return;
                    const css = document.createElement('link');
                    css.id = 'leaflet-css';
                    css.rel = 'stylesheet';
                    css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                    document.head.appendChild(css);
                };

                const loadLeafletJs = (callback) => {
                    if (typeof L !== 'undefined') {
                        callback();
                        return;
                    }
                    const existing = document.getElementById('leaflet-js');
                    if (existing) {
                        existing.addEventListener('load', callback);
                        return;
                    }
                    const js = document.createElement('script');
                    js.id = 'leaflet-js';
                    js.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                    js.onload = callback;
                    document.head.appendChild(js);
                };

                // Renderiranje detalja
                const prikaziDojavu = (dojavaId) => {
                    const d = window.FireOps.data.dojave.find(x => x.id === dojavaId);
                    if (!d) return;

                    const prio = d.prioritet === 'kriticna' 
                        ? { color: '#DC2626', label: '🔴 KRITIČNA', bg: '#FEE2E2' }
                        : d.prioritet === 'visoka' 
                            ? { color: '#F59E0B', label: '🟡 VISOKA', bg: '#FEF3C7' }
                            : { color: '#10B981', label: '🟢 STANDARDNA', bg: '#D1FAE5' };

                    const statusLabel = d.status === 'zaprimljena' ? 'ZAPRIMLJENA' 
                        : d.status === 'dodijeljena' ? 'DODIJELJENA' 
                        : d.status === 'u_tijeku' ? 'U TIJEKU' 
                        : d.status.toUpperCase();

                    const tipLabel = ({
                        'olujno_nevrijeme': '⛈ Olujno nevrijeme',
                        'poplava': '🌊 Poplava',
                        'pozar': '🔥 Požar',
                        'snijeg_led': '❄ Snijeg/led',
                        'klizište': '⛰ Klizište',
                        'tuca': '🧊 Tuča',
                        'potres': '🌍 Potres',
                    })[d.tip] || '❓ Ostalo';

                    const ugrozenoLabel = d.ugrozenost === 'da' ? '⚠ DA — ima ugroženih' 
                        : d.ugrozenost === 'ne' ? 'NE — nema ugroženih' 
                        : 'Nepoznato';

                    document.getElementById('fireops-detalji').innerHTML = `
                        <div style="background: ${prio.bg}; padding: 16px; border-bottom: 1px solid #E5E7EB;">
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                                <span style="font-size: 11px; font-weight: 800; background: ${prio.color}; color: white; padding: 4px 10px; border-radius: 4px;">${prio.label}</span>
                                <span style="font-size: 10px; font-weight: 700; background: white; color: #374151; padding: 3px 8px; border-radius: 4px;">${statusLabel}</span>
                            </div>
                            <div style="font-size: 22px; font-weight: 900; color: #111827; margin-top: 4px;">#${d.broj}</div>
                            <div style="font-size: 13px; color: #4B5563; margin-top: 2px;">${d.vrijeme} • prije ${d.protekloMinuta} min</div>
                        </div>
                        <div style="padding: 16px;">
                            <div style="margin-bottom: 16px;">
                                <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">📍 Lokacija</div>
                                <div style="font-size: 14px; font-weight: 600; color: #111827;">${d.adresa}</div>
                                <div style="font-size: 12px; color: #6B7280; margin-top: 2px;">${d.jls}</div>
                            </div>
                            <div style="margin-bottom: 16px;">
                                <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">Tip nepogode</div>
                                <div style="font-size: 14px; color: #111827;">${tipLabel}</div>
                            </div>
                            <div style="margin-bottom: 16px;">
                                <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">Ugroženost ljudi</div>
                                <div style="font-size: 14px; color: ${d.ugrozenost === 'da' ? '#DC2626' : '#111827'}; font-weight: ${d.ugrozenost === 'da' ? '700' : '500'};">${ugrozenoLabel}</div>
                            </div>
                            ${d.opis ? `
                                <div style="margin-bottom: 16px;">
                                    <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">Opis situacije</div>
                                    <div style="font-size: 13px; color: #374151; line-height: 1.5; background: #F9FAFB; padding: 10px; border-radius: 6px;">${d.opis}</div>
                                </div>
                            ` : ''}
                            <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 20px; padding-top: 16px; border-top: 2px solid #F3F4F6;">
                                <a href="/admin/dojavas/${d.id}/edit" 
                                   style="display: flex; align-items: center; justify-content: center; gap: 8px; background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%); color: white; padding: 12px 16px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 13px; transition: all 0.2s;"
                                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(220, 38, 38, 0.4)';"
                                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                    Otvori dojavu →
                                </a>
                            </div>
                        </div>
                    `;
                };

                const prikaziPostrojbu = (postrojbaId) => {
                    const p = window.FireOps.data.postrojbe.find(x => x.id === postrojbaId);
                    if (!p) return;

                    const tipColors = {
                        'DVD': '#10B981',
                        'JVP': '#3B82F6',
                        'IDVD': '#F59E0B',
                        'ostalo': '#6B7280',
                    };
                    const boja = tipColors[p.tip] || tipColors['ostalo'];

                    document.getElementById('fireops-detalji').innerHTML = `
                        <div style="background: ${boja}; padding: 16px; color: white;">
                            <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9;">${p.tip}</div>
                            <div style="font-size: 18px; font-weight: 800; margin-top: 4px; line-height: 1.3;">${p.naziv}</div>
                        </div>
                        <div style="padding: 16px;">
                            <div style="margin-bottom: 16px;">
                                <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">Status</div>
                                ${p.operativna 
                                    ? '<div style="font-size: 14px; color: #059669; font-weight: 700;">✓ Operativno spremna</div>'
                                    : '<div style="font-size: 14px; color: #DC2626; font-weight: 700;">⚠ Nije operativna</div>'}
                            </div>
                            <div style="margin-bottom: 16px;">
                                <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">📍 Adresa</div>
                                <div style="font-size: 14px; font-weight: 600; color: #111827;">${p.adresa}</div>
                                <div style="font-size: 12px; color: #6B7280; margin-top: 2px;">${p.jls}</div>
                            </div>
                            ${p.telefon ? `
                                <div style="margin-bottom: 16px;">
                                    <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">📞 Telefon</div>
                                    <div style="font-size: 14px; color: #111827;">${p.telefon}</div>
                                </div>
                            ` : ''}
                            <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 20px; padding-top: 16px; border-top: 2px solid #F3F4F6;">
                                <a href="/admin/postrojbas/${p.id}" 
                                   style="display: flex; align-items: center; justify-content: center; gap: 8px; background: ${boja}; color: white; padding: 12px 16px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 13px;">
                                    Otvori postrojbu →
                                </a>
                            </div>
                        </div>
                    `;
                };

                const initMap = () => {
                    const mapEl = document.getElementById('fireops-map');
                    if (!mapEl || mapEl._leaflet_id) return;

                    const dataStr = mapEl.getAttribute('data-mapa');
                    if (!dataStr) {
                        console.error('FireOps: nedostaju podaci mape');
                        return;
                    }

                    let parsed;
                    try {
                        parsed = JSON.parse(dataStr);
                    } catch (e) {
                        console.error('FireOps: greška u parsiranju', e);
                        return;
                    }

                    window.FireOps.data = parsed;

                    const map = L.map('fireops-map', {
                        zoomControl: true,
                        attributionControl: true,
                    }).setView([45.35, 17.65], 10);

                    window.FireOps.map = map;

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap',
                        maxZoom: 18,
                    }).addTo(map);

                    const createPin = (color, size = 12, borderColor = 'white') => {
                        return L.divIcon({
                            className: 'fireops-pin',
                            html: '<div style="width:' + size + 'px;height:' + size + 'px;border-radius:50%;background:' + color + ';border:2px solid ' + borderColor + ';box-shadow:0 0 0 1px ' + color + ', 0 2px 4px rgba(0,0,0,0.3);"></div>',
                            iconSize: [size + 4, size + 4],
                            iconAnchor: [(size + 4) / 2, (size + 4) / 2],
                        });
                    };

                    const postrojbaBoja = {
                        'DVD': '#10B981',
                        'JVP': '#3B82F6',
                        'IDVD': '#F59E0B',
                        'ostalo': '#6B7280',
                    };

                    parsed.postrojbe.forEach(function(p) {
                        const boja = postrojbaBoja[p.tip] || postrojbaBoja['ostalo'];
                        const marker = L.marker([p.lat, p.lng], {
                            icon: createPin(boja, 12),
                        }).addTo(map);
                        
                        marker.on('click', function() {
                            prikaziPostrojbu(p.id);
                        });

                        window.FireOps.markers.postrojbe[p.id] = marker;
                    });

                    const dojavaBoja = {
                        'kriticna': '#DC2626',
                        'visoka': '#F59E0B',
                        'standardna': '#10B981',
                    };

                    parsed.dojave.forEach(function(d) {
                        const boja = dojavaBoja[d.prioritet] || '#6B7280';
                        const marker = L.marker([d.lat, d.lng], {
                            icon: createPin(boja, 18, 'white'),
                        }).addTo(map);
                        
                        marker.on('click', function() {
                            prikaziDojavu(d.id);
                        });

                        window.FireOps.markers.dojave[d.id] = marker;
                    });

                    if (parsed.dojave.length > 0) {
                        const bounds = L.latLngBounds(parsed.dojave.map(function(d) { return [d.lat, d.lng]; }));
                        map.fitBounds(bounds, { padding: [60, 60], maxZoom: 13 });
                    }

                    // Klik na karticu dojave lijevo
                    document.querySelectorAll('.fireops-dojava-card').forEach(function(card) {
                        card.addEventListener('click', function() {
                            const dojavaId = parseInt(this.getAttribute('data-dojava-id'));
                            const lat = parseFloat(this.getAttribute('data-lat'));
                            const lng = parseFloat(this.getAttribute('data-lng'));
                            
                            if (!isNaN(lat) && !isNaN(lng)) {
                                map.flyTo([lat, lng], 15, { duration: 0.8 });
                                
                                const marker = window.FireOps.markers.dojave[dojavaId];
                                if (marker) {
                                    setTimeout(() => marker.openPopup(), 800);
                                }
                            }
                            
                            prikaziDojavu(dojavaId);
                        });
                    });
                };

                const start = () => {
                    loadLeafletCss();
                    loadLeafletJs(initMap);
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', start);
                } else {
                    start();
                }
            })();
        </script>
    @endpush
</x-filament-panels::page>