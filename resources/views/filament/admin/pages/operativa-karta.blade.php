<x-filament-panels::page>
    <div wire:ignore.self>
        <style>
            body, html { overflow: hidden !important; }
            .fi-main { padding: 0 !important; max-width: 100% !important; }
            .fi-main-ctn { padding: 0 !important; }
            section.fi-main { padding: 0 !important; }
            .fi-resource-list-records-page, .fi-page { padding: 0 !important; }
        </style>

        <div id="fireops-fullmap-container" style="position: fixed; top: 64px; left: 0; right: 0; bottom: 0; background: #1E40AF;">
            
            {{-- HEADER OVERLAY --}}
            <div style="position: absolute; top: 0; left: 0; right: 0; padding: 14px 24px; background: linear-gradient(to bottom, rgba(0,0,0,0.75), transparent); color: white; z-index: 1000; pointer-events: none;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                    <div>
                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; opacity: 0.9;">FireOps PSŽ • Operativna karta</div>
                        <div style="font-size: 22px; font-weight: 900; margin-top: 2px; letter-spacing: -0.5px;">
                            <span style="opacity: 0.85;">{{ $brojPostrojbi }}</span> postrojbi · 
                            <span style="opacity: 0.85;">{{ $brojDojava }}</span> dojava · 
                            <span style="opacity: 0.85;">{{ $brojIntervencija }}</span> intervencija · 
                            <span style="opacity: 0.85;">{{ $brojTimovaNaTerenu }}</span> timova na terenu
                        </div>
                    </div>
                    <div style="text-align: right; pointer-events: auto;">
                        <div style="font-size: 10px; opacity: 0.9; text-transform: uppercase; letter-spacing: 1px;">Stanje</div>
                        <div style="font-size: 18px; font-weight: 900; color: {{ $stanje['boja'] === '#DC2626' ? '#FCA5A5' : ($stanje['boja'] === '#F97316' ? '#FED7AA' : '#86EFAC') }};">
                            {{ $stanje['naslov'] }}
                        </div>
                        <a href="/admin/dispatcher" target="_blank" 
                           style="display: inline-block; margin-top: 6px; background: rgba(255,255,255,0.2); color: white; padding: 6px 14px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 700; backdrop-filter: blur(8px);">
                            📋 Otvori dispečerski centar →
                        </a>
                    </div>
                </div>
            </div>

            {{-- LEGENDA --}}
            <div style="position: absolute; bottom: 20px; left: 20px; background: rgba(255,255,255,0.97); padding: 14px 18px; border-radius: 12px; z-index: 1000; box-shadow: 0 8px 24px rgba(0,0,0,0.25);">
                <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280; margin-bottom: 10px;">LEGENDA</div>
                <div style="display: flex; flex-direction: column; gap: 6px; font-size: 12px; color: #111827;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="width: 14px; height: 14px; border-radius: 50%; background: #10B981; border: 2px solid white; box-shadow: 0 0 0 1px #10B981;"></span> 
                        <strong>DVD</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="width: 14px; height: 14px; border-radius: 50%; background: #3B82F6; border: 2px solid white; box-shadow: 0 0 0 1px #3B82F6;"></span> 
                        <strong>JVP</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="width: 14px; height: 14px; border-radius: 50%; background: #F59E0B; border: 2px solid white; box-shadow: 0 0 0 1px #F59E0B;"></span> 
                        <strong>IDVD</strong>
                    </div>
                    <div style="border-top: 1px solid #E5E7EB; margin: 4px 0; padding-top: 4px;"></div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="width: 18px; height: 18px; border-radius: 50%; background: #DC2626; border: 3px solid white; box-shadow: 0 0 0 2px #DC2626;"></span>
                        <strong>Dojava</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="width: 20px; height: 20px; border-radius: 4px; background: #DC2626; border: 3px solid white; box-shadow: 0 0 0 2px #DC2626;"></span>
                        <strong>Intervencija</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 18px;">🚒</span>
                        <strong>Tim na terenu</strong>
                    </div>
                </div>
            </div>

            {{-- KONTROLNI PANEL DESNO --}}
            <div style="position: absolute; bottom: 20px; right: 20px; background: rgba(255,255,255,0.97); padding: 12px 16px; border-radius: 12px; z-index: 1000; box-shadow: 0 8px 24px rgba(0,0,0,0.25); display: flex; flex-direction: column; gap: 8px;">
                <div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7280;">Brzi pristup</div>
                <a href="/admin/dojavas/create" target="_blank"
                   style="background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%); color: white; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 800; text-align: center;">
                    ➕ Nova dojava
                </a>
                <a href="/admin/intervencijas" target="_blank"
                   style="background: #F3F4F6; color: #374151; border: 1px solid #D1D5DB; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 700; text-align: center;">
                    🔥 Intervencije
                </a>
            </div>
            
            {{-- MAPA --}}
            <div id="fireops-fullmap" 
                 data-mapa="{{ $mapaData }}"
                 style="width: 100%; height: 100%;"></div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                if (window._fireopsFullMapInit) return;
                window._fireopsFullMapInit = true;

                const loadLeafletCss = () => {
                    if (document.getElementById('leaflet-css')) return;
                    const css = document.createElement('link');
                    css.id = 'leaflet-css';
                    css.rel = 'stylesheet';
                    css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                    document.head.appendChild(css);
                };

                const loadLeafletJs = (callback) => {
                    if (typeof L !== 'undefined') { callback(); return; }
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

                const initMap = () => {
                    const mapEl = document.getElementById('fireops-fullmap');
                    if (!mapEl || mapEl._leaflet_id) return;

                    const dataStr = mapEl.getAttribute('data-mapa');
                    if (!dataStr) return;

                    let parsed;
                    try { parsed = JSON.parse(dataStr); } catch (e) { return; }

                    const map = L.map('fireops-fullmap', { zoomControl: true }).setView([45.35, 17.65], 10);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap',
                        maxZoom: 18,
                    }).addTo(map);

                    const postrojbaBoja = {
                        'DVD': '#10B981', 'JVP': '#3B82F6', 'IDVD': '#F59E0B', 'ostalo': '#6B7280',
                    };
                    const dojavaBoja = {
                        'kriticna': '#DC2626', 'visoka': '#F59E0B', 'standardna': '#10B981',
                    };

                    const createCirclePin = (color, size, borderColor) => {
                        return L.divIcon({
                            className: 'fireops-pin',
                            html: '<div style="width:' + size + 'px;height:' + size + 'px;border-radius:50%;background:' + color + ';border:2px solid ' + borderColor + ';box-shadow:0 0 0 1px ' + color + ', 0 2px 6px rgba(0,0,0,0.4);"></div>',
                            iconSize: [size + 4, size + 4],
                            iconAnchor: [(size + 4) / 2, (size + 4) / 2],
                        });
                    };

                    const createSquarePin = (color, size) => {
                        return L.divIcon({
                            className: 'fireops-pin-square',
                            html: '<div style="width:' + size + 'px;height:' + size + 'px;border-radius:4px;background:' + color + ';border:3px solid white;box-shadow:0 0 0 2px ' + color + ', 0 4px 10px rgba(0,0,0,0.4);"></div>',
                            iconSize: [size + 6, size + 6],
                            iconAnchor: [(size + 6) / 2, (size + 6) / 2],
                        });
                    };

                    const createEmojiPin = (emoji, size) => {
                        return L.divIcon({
                            className: 'fireops-pin-emoji',
                            html: '<div style="font-size:' + size + 'px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));">' + emoji + '</div>',
                            iconSize: [size + 4, size + 4],
                            iconAnchor: [(size + 4) / 2, (size + 4) / 2],
                        });
                    };

                    // POSTROJBE
                    parsed.postrojbe.forEach(p => {
                        const boja = postrojbaBoja[p.tip] || postrojbaBoja['ostalo'];
                        const marker = L.marker([p.lat, p.lng], { icon: createCirclePin(boja, 12, 'white') }).addTo(map);
                        marker.bindPopup(
                            '<div style="font-family:Inter,sans-serif;padding:4px;min-width:180px;">' +
                                '<div style="font-weight:800;font-size:13px;color:#111827;">' + p.naziv + '</div>' +
                                '<div style="font-size:11px;color:#6B7280;margin-top:2px;">' + p.tip + ' • ' + p.jls + '</div>' +
                                '<div style="font-size:11px;color:' + (p.operativna ? '#059669' : '#DC2626') + ';margin-top:6px;font-weight:600;">' +
                                    (p.operativna ? '✓ Operativno spremna' : '⚠ Nije operativna') +
                                '</div>' +
                            '</div>'
                        );
                    });

                    // DOJAVE
                    parsed.dojave.forEach(d => {
                        const boja = dojavaBoja[d.prioritet] || '#6B7280';
                        const marker = L.marker([d.lat, d.lng], { icon: createCirclePin(boja, 20, 'white') }).addTo(map);
                        marker.bindPopup(
                            '<div style="font-family:Inter,sans-serif;padding:4px;min-width:200px;">' +
                                '<div style="font-size:10px;font-weight:800;color:' + boja + ';text-transform:uppercase;letter-spacing:0.5px;">' + d.prioritet + ' • DOJAVA</div>' +
                                '<div style="font-weight:800;font-size:14px;color:#111827;margin-top:2px;">#' + d.broj + '</div>' +
                                '<div style="font-size:12px;color:#374151;margin-top:4px;">' + d.adresa + '</div>' +
                                '<div style="font-size:11px;color:#6B7280;margin-top:2px;">' + d.jls + ' • ' + d.vrijeme + '</div>' +
                                '<a href="/admin/dojavas/' + d.id + '/edit" target="_blank" style="display:inline-block;margin-top:8px;background:#DC2626;color:white;padding:5px 12px;border-radius:6px;font-size:11px;font-weight:700;text-decoration:none;">Otvori dojavu →</a>' +
                            '</div>'
                        );
                    });

                    // INTERVENCIJE — kvadrat
                    parsed.intervencije.forEach(i => {
                        const boja = dojavaBoja[i.prioritet] || '#6B7280';
                        const marker = L.marker([i.lat, i.lng], { icon: createSquarePin(boja, 22) }).addTo(map);
                        marker.bindPopup(
                            '<div style="font-family:Inter,sans-serif;padding:4px;min-width:220px;">' +
                                '<div style="font-size:10px;font-weight:800;color:' + boja + ';text-transform:uppercase;letter-spacing:0.5px;">🔥 INTERVENCIJA • ' + i.prioritet + '</div>' +
                                '<div style="font-weight:800;font-size:14px;color:#111827;margin-top:2px;">' + i.naziv + '</div>' +
                                '<div style="font-size:11px;color:#6B7280;margin-top:2px;">#' + i.broj + ' • ' + i.jls + '</div>' +
                                '<div style="font-size:12px;color:#374151;margin-top:6px;">' +
                                    '<strong>' + i.brojTimova + '</strong> tim' + (i.brojTimova === 1 ? '' : 'ova') + ' angažirano' +
                                '</div>' +
                                '<a href="/admin/intervencijas/' + i.id + '/edit" target="_blank" style="display:inline-block;margin-top:8px;background:#DC2626;color:white;padding:5px 12px;border-radius:6px;font-size:11px;font-weight:700;text-decoration:none;">Vodi intervenciju →</a>' +
                            '</div>'
                        );
                    });

                    // TIMOVI — emoji 🚒
                    parsed.timovi.forEach(t => {
                        // Pomakni malo da se ne preklapa s intervencijom
                        const lat = t.lat + (Math.random() - 0.5) * 0.003;
                        const lng = t.lng + (Math.random() - 0.5) * 0.003;
                        const marker = L.marker([lat, lng], { icon: createEmojiPin('🚒', 24) }).addTo(map);
                        marker.bindPopup(
                            '<div style="font-family:Inter,sans-serif;padding:4px;min-width:180px;">' +
                                '<div style="font-size:10px;font-weight:800;color:#3B82F6;text-transform:uppercase;letter-spacing:0.5px;">🚒 TIM • ' + t.status.toUpperCase().replace(/_/g, ' ') + '</div>' +
                                '<div style="font-weight:800;font-size:14px;color:#111827;margin-top:2px;">' + t.naziv + '</div>' +
                                '<div style="font-size:11px;color:#6B7280;margin-top:2px;">👑 ' + t.zapovjednik + '</div>' +
                                '<div style="font-size:11px;color:#6B7280;">🧑‍🚒 ' + t.brojClanova + ' članova</div>' +
                                '<div style="font-size:11px;color:#374151;margin-top:4px;">→ ' + t.intervencija + '</div>' +
                                '<a href="/admin/tims/' + t.id + '/edit" target="_blank" style="display:inline-block;margin-top:6px;background:#3B82F6;color:white;padding:4px 10px;border-radius:5px;font-size:11px;font-weight:700;text-decoration:none;">Upravljaj timom →</a>' +
                            '</div>'
                        );
                    });

                    // Fit bounds
                    if (parsed.dojave.length > 0 || parsed.intervencije.length > 0) {
                        const tocke = [
                            ...parsed.dojave.map(d => [d.lat, d.lng]),
                            ...parsed.intervencije.map(i => [i.lat, i.lng]),
                        ];
                        if (tocke.length > 0) {
                            const bounds = L.latLngBounds(tocke);
                            map.fitBounds(bounds, { padding: [80, 80], maxZoom: 13 });
                        }
                    }
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
