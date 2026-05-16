<x-filament-panels::page>
    @php
        $stanjeBoja = match($stanje['boja']) {
            '#DC2626' => '#FCA5A5',
            '#F97316' => '#FED7AA',
            default => '#86EFAC',
        };
    @endphp
    <div wire:ignore.self>
        <style>
            body, html { overflow: hidden !important; }
            .fi-main { padding: 0 !important; max-width: 100% !important; }
            .fi-main-ctn { padding: 0 !important; }
            section.fi-main { padding: 0 !important; }
            .fi-page { padding: 0 !important; }
            .fi-page-header-main-ctn { padding: 8px 16px !important; }
            
            .fo-toggle { transition: all 0.15s; cursor: pointer; user-select: none; }
            .fo-toggle:hover { transform: translateX(2px); }
            .fo-toggle-aktivan { background: rgba(255,255,255,1); box-shadow: 0 2px 8px rgba(0,0,0,0.12); }
            .fo-toggle-neaktivan { background: rgba(255,255,255,0.4); opacity: 0.55; }
            
            #fireops-side-panel {
                position: fixed;
                top: 64px;
                right: -440px;
                width: 420px;
                max-width: calc(100vw - 40px);
                bottom: 0;
                background: white;
                box-shadow: -8px 0 32px rgba(0,0,0,0.18);
                z-index: 2000;
                transition: right 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                overflow-y: auto;
                display: flex;
                flex-direction: column;
            }
            #fireops-side-panel.open { right: 0; }
        </style>

        <div id="fireops-fullmap-container" style="position: fixed; top: 64px; left: 0; right: 0; bottom: 0; background: #1E40AF;">
            
            <div style="position: absolute; top: 0; left: 0; right: 0; padding: 14px 24px; background: linear-gradient(to bottom, rgba(0,0,0,0.78), transparent); color: white; z-index: 1000; pointer-events: none;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                    <div>
                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; opacity: 0.9;">
                            FireOps PSŽ • Operativna karta 
                            <span id="fireops-last-refresh" style="opacity: 0.7; font-size: 10px; margin-left: 6px;"></span>
                        </div>
                        <div style="font-size: 22px; font-weight: 900; margin-top: 2px; letter-spacing: -0.5px;">
                            <span id="fireops-stat-postrojbi" style="opacity: 0.85;">{{ $brojPostrojbi }}</span> postrojbi · 
                            <span id="fireops-stat-dojava" style="opacity: 0.85;">{{ $brojDojava }}</span> dojava · 
                            <span id="fireops-stat-intervencija" style="opacity: 0.85;">{{ $brojIntervencija }}</span> intervencija · 
                            <span id="fireops-stat-timova" style="opacity: 0.85;">{{ $brojTimovaNaTerenu }}</span> timova na terenu
                        </div>
                    </div>
                    <div style="text-align: right; pointer-events: auto;">
                        <div style="font-size: 10px; opacity: 0.9; text-transform: uppercase; letter-spacing: 1px;">Stanje</div>
                        <div style="font-size: 18px; font-weight: 900; color: {{ $stanjeBoja }};">
                            {{ $stanje['naslov'] }}
                        </div>
                        <a href="/admin/dispatcher" target="_blank" 
                           style="display: inline-block; margin-top: 6px; background: rgba(255,255,255,0.22); color: white; padding: 6px 14px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 700; backdrop-filter: blur(8px);">
                            📋 Otvori dispečerski centar →
                        </a>
                    </div>
                </div>
            </div>

            <div style="position: absolute; top: 110px; right: 20px; background: rgba(255,255,255,0.95); padding: 12px; border-radius: 12px; z-index: 1000; box-shadow: 0 8px 24px rgba(0,0,0,0.2); backdrop-filter: blur(10px); min-width: 200px;">
                <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #6B7280; margin-bottom: 10px;">PRIKAZ NA KARTI</div>
                
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <label class="fo-toggle fo-toggle-aktivan" data-layer="dojave"
                           style="padding: 8px 10px; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" checked style="width: 16px; height: 16px; accent-color: #DC2626; cursor: pointer;">
                        <span style="display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: #111827;">
                            <span style="width: 12px; height: 12px; border-radius: 50%; background: #DC2626; border: 2px solid white; box-shadow: 0 0 0 1px #DC2626;"></span>
                            Dojave
                        </span>
                        <span style="margin-left: auto; font-size: 11px; font-weight: 700; color: #6B7280;" id="fireops-cnt-dojave">{{ $brojDojava }}</span>
                    </label>

                    <label class="fo-toggle fo-toggle-aktivan" data-layer="intervencije"
                           style="padding: 8px 10px; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" checked style="width: 16px; height: 16px; accent-color: #DC2626; cursor: pointer;">
                        <span style="display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: #111827;">
                            <span style="width: 12px; height: 12px; border-radius: 3px; background: #DC2626; border: 2px solid white; box-shadow: 0 0 0 1px #DC2626;"></span>
                            Intervencije
                        </span>
                        <span style="margin-left: auto; font-size: 11px; font-weight: 700; color: #6B7280;" id="fireops-cnt-intervencije">{{ $brojIntervencija }}</span>
                    </label>

                    <label class="fo-toggle fo-toggle-aktivan" data-layer="timovi"
                           style="padding: 8px 10px; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" checked style="width: 16px; height: 16px; accent-color: #3B82F6; cursor: pointer;">
                        <span style="display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: #111827;">
                            <span style="font-size: 14px;">🚒</span>
                            Timovi
                        </span>
                        <span style="margin-left: auto; font-size: 11px; font-weight: 700; color: #6B7280;" id="fireops-cnt-timovi">{{ $brojTimovaNaTerenu }}</span>
                    </label>

                    <div style="border-top: 1px solid #E5E7EB; margin: 4px 0;"></div>

                    <label class="fo-toggle fo-toggle-neaktivan" data-layer="postrojbe"
                           style="padding: 8px 10px; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" style="width: 16px; height: 16px; accent-color: #10B981; cursor: pointer;">
                        <span style="display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: #111827;">
                            <span style="width: 10px; height: 10px; border-radius: 50%; background: #10B981; border: 2px solid white; box-shadow: 0 0 0 1px #10B981;"></span>
                            Postrojbe
                        </span>
                        <span style="margin-left: auto; font-size: 11px; font-weight: 700; color: #6B7280;" id="fireops-cnt-postrojbe">{{ $brojPostrojbi }}</span>
                    </label>
                </div>
            </div>

            <div style="position: absolute; bottom: 20px; left: 20px; background: rgba(255,255,255,0.95); padding: 12px 16px; border-radius: 12px; z-index: 1000; box-shadow: 0 8px 24px rgba(0,0,0,0.2); backdrop-filter: blur(10px); max-width: 240px;">
                <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #6B7280; margin-bottom: 8px;">LEGENDA</div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px; font-size: 11px; color: #111827;">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span style="width: 12px; height: 12px; border-radius: 50%; background: #DC2626; border: 2px solid white; box-shadow: 0 0 0 1px #DC2626;"></span>
                        <strong>Kritična</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span style="width: 12px; height: 12px; border-radius: 50%; background: #F59E0B; border: 2px solid white; box-shadow: 0 0 0 1px #F59E0B;"></span>
                        <strong>Visoka</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span style="width: 12px; height: 12px; border-radius: 50%; background: #10B981; border: 2px solid white; box-shadow: 0 0 0 1px #10B981;"></span>
                        <strong>Standardna</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span style="width: 12px; height: 12px; border-radius: 3px; background: #DC2626; border: 2px solid white;"></span>
                        <strong>Intervencija</strong>
                    </div>
                </div>
            </div>

            <div style="position: absolute; bottom: 20px; right: 20px; background: rgba(255,255,255,0.95); padding: 10px 12px; border-radius: 12px; z-index: 1000; box-shadow: 0 8px 24px rgba(0,0,0,0.2); display: flex; gap: 6px; backdrop-filter: blur(10px);">
                <a href="/admin/dojavas/create" target="_blank"
                   style="background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%); color: white; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 800;">
                    ➕ Nova dojava
                </a>
                <a href="/admin/intervencijas" target="_blank"
                   style="background: #F3F4F6; color: #374151; border: 1px solid #D1D5DB; padding: 8px 14px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 700;">
                    🔥 Intervencije
                </a>
            </div>
            
            <div wire:ignore id="fireops-fullmap-wrapper" style="width: 100%; height: 100%;">
                <div id="fireops-fullmap" 
                     data-mapa-init="{{ $mapaData }}"
                     style="width: 100%; height: 100%;"></div>
            </div>
            
            <div id="fireops-mapa-podaci" data-mapa="{{ $mapaData }}" style="display: none;"></div>
        </div>

        <div id="fireops-side-panel">
            <div id="fireops-side-panel-content" style="flex: 1; display: flex; flex-direction: column;"></div>
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
                    if (existing) { existing.addEventListener('load', callback); return; }
                    const js = document.createElement('script');
                    js.id = 'leaflet-js';
                    js.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                    js.onload = callback;
                    document.head.appendChild(js);
                };

                const postrojbaBoja = { 'DVD': '#10B981', 'JVP': '#3B82F6', 'IDVD': '#F59E0B', 'ostalo': '#6B7280' };
                const dojavaBoja = { 'kriticna': '#DC2626', 'visoka': '#F59E0B', 'standardna': '#10B981' };

                const escapeHtml = (s) => {
                    if (s === null || s === undefined) return '';
                    return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
                };

                const createCirclePin = (color, size, borderColor) => L.divIcon({
                    className: 'fireops-pin',
                    html: '<div style="width:' + size + 'px;height:' + size + 'px;border-radius:50%;background:' + color + ';border:2px solid ' + borderColor + ';box-shadow:0 0 0 1px ' + color + ', 0 2px 6px rgba(0,0,0,0.4); cursor:pointer;"></div>',
                    iconSize: [size + 4, size + 4],
                    iconAnchor: [(size + 4) / 2, (size + 4) / 2],
                });

                const createSquarePin = (color, size) => L.divIcon({
                    className: 'fireops-pin-square',
                    html: '<div style="width:' + size + 'px;height:' + size + 'px;border-radius:4px;background:' + color + ';border:3px solid white;box-shadow:0 0 0 2px ' + color + ', 0 4px 10px rgba(0,0,0,0.4); cursor:pointer;"></div>',
                    iconSize: [size + 6, size + 6],
                    iconAnchor: [(size + 6) / 2, (size + 6) / 2],
                });

                const createEmojiPin = (emoji, size) => L.divIcon({
                    className: 'fireops-pin-emoji',
                    html: '<div style="font-size:' + size + 'px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5)); cursor:pointer;">' + emoji + '</div>',
                    iconSize: [size + 4, size + 4],
                    iconAnchor: [(size + 4) / 2, (size + 4) / 2],
                });

                const openPanel = (html) => {
                    const panel = document.getElementById('fireops-side-panel');
                    const content = document.getElementById('fireops-side-panel-content');
                    if (!panel || !content) return;
                    content.innerHTML = html;
                    panel.classList.add('open');
                };

                const closePanel = () => {
                    const panel = document.getElementById('fireops-side-panel');
                    if (panel) panel.classList.remove('open');
                };

                window._fireopsClosePanel = closePanel;

                document.addEventListener('click', (e) => {
                    const panel = document.getElementById('fireops-side-panel');
                    if (!panel || !panel.classList.contains('open')) return;
                    if (panel.contains(e.target)) return;
                    if (e.target.closest('.fireops-pin, .fireops-pin-square, .fireops-pin-emoji, .leaflet-marker-icon')) return;
                    closePanel();
                });

                const renderDojavaPanel = (d) => {
                    const boja = dojavaBoja[d.prioritet] || '#6B7280';
                    const tipText = (d.tip || '?').replace(/_/g, ' ');
                    return '<div style="display:flex; flex-direction:column; height:100%;">' +
                        '<div style="padding: 18px 22px; background: linear-gradient(135deg, ' + boja + ' 0%, ' + boja + 'CC 100%); color: white; position: relative;">' +
                            '<button onclick="window._fireopsClosePanel()" style="position:absolute; top:14px; right:14px; background:rgba(0,0,0,0.25); color:white; border:none; width:32px; height:32px; border-radius:50%; cursor:pointer; font-size:16px; font-weight:800;">✕</button>' +
                            '<div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.9; margin-bottom: 4px;">📞 DOJAVA • ' + escapeHtml(d.prioritet.toUpperCase()) + '</div>' +
                            '<div style="font-size: 22px; font-weight: 900; line-height: 1.2;">#' + escapeHtml(d.broj) + '</div>' +
                            '<div style="font-size: 13px; opacity: 0.95; margin-top: 4px;">' + escapeHtml(tipText) + '</div>' +
                        '</div>' +
                        '<div style="padding: 18px 22px; flex: 1;">' +
                            '<div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">📍 LOKACIJA</div>' +
                            '<div style="font-size: 15px; font-weight: 700; color: #111827;">' + escapeHtml(d.adresa) + '</div>' +
                            '<div style="font-size: 12px; color: #6B7280; margin-top: 2px;">' + escapeHtml(d.jls) + '</div>' +
                            '<div style="border-top: 1px solid #E5E7EB; margin: 18px 0;"></div>' +
                            '<div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">🕐 ZAPRIMLJENO</div>' +
                            '<div style="font-size: 14px; color: #111827;">' + escapeHtml(d.vrijeme) + '</div>' +
                            '<div style="border-top: 1px solid #E5E7EB; margin: 18px 0;"></div>' +
                            '<div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">STATUS</div>' +
                            '<div style="font-size: 14px; color: #111827; text-transform: capitalize;">' + escapeHtml(d.status.replace(/_/g, ' ')) + '</div>' +
                        '</div>' +
                        '<div style="padding: 14px 22px; background: #F9FAFB; border-top: 1px solid #E5E7EB; display: flex; gap: 8px;">' +
                            '<a href="/admin/dojavas/' + d.id + '/edit" target="_blank" style="flex:1; background: ' + boja + '; color: white; padding: 12px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 800; text-align: center;">📋 Otvori dojavu</a>' +
                        '</div>' +
                    '</div>';
                };

                const renderIntervencijaPanel = (i) => {
                    const boja = dojavaBoja[i.prioritet] || '#6B7280';
                    return '<div style="display:flex; flex-direction:column; height:100%;">' +
                        '<div style="padding: 18px 22px; background: linear-gradient(135deg, ' + boja + ' 0%, ' + boja + 'CC 100%); color: white; position: relative;">' +
                            '<button onclick="window._fireopsClosePanel()" style="position:absolute; top:14px; right:14px; background:rgba(0,0,0,0.25); color:white; border:none; width:32px; height:32px; border-radius:50%; cursor:pointer; font-size:16px; font-weight:800;">✕</button>' +
                            '<div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.9; margin-bottom: 4px;">🔥 INTERVENCIJA • ' + escapeHtml(i.prioritet.toUpperCase()) + '</div>' +
                            '<div style="font-size: 22px; font-weight: 900; line-height: 1.2;">' + escapeHtml(i.naziv) + '</div>' +
                            '<div style="font-size: 12px; opacity: 0.95; margin-top: 4px;">#' + escapeHtml(i.broj) + '</div>' +
                        '</div>' +
                        '<div style="padding: 18px 22px; flex: 1;">' +
                            '<div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">📍 LOKACIJA</div>' +
                            '<div style="font-size: 15px; font-weight: 700; color: #111827;">' + escapeHtml(i.adresa || '—') + '</div>' +
                            '<div style="font-size: 12px; color: #6B7280; margin-top: 2px;">' + escapeHtml(i.jls) + '</div>' +
                            '<div style="border-top: 1px solid #E5E7EB; margin: 18px 0;"></div>' +
                            '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">' +
                                '<div>' +
                                    '<div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">TIMOVA</div>' +
                                    '<div style="font-size: 24px; font-weight: 900; color: ' + boja + ';">' + i.brojTimova + '</div>' +
                                '</div>' +
                                '<div>' +
                                    '<div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">OTVORENO</div>' +
                                    '<div style="font-size: 14px; font-weight: 700; color: #111827;">' + escapeHtml(i.vrijeme) + '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div style="border-top: 1px solid #E5E7EB; margin: 18px 0;"></div>' +
                            '<div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">TIP</div>' +
                            '<div style="font-size: 14px; color: #111827; text-transform: capitalize;">' + escapeHtml((i.tip || '?').replace(/_/g, ' ')) + '</div>' +
                        '</div>' +
                        '<div style="padding: 14px 22px; background: #F9FAFB; border-top: 1px solid #E5E7EB; display: flex; gap: 8px;">' +
                            '<a href="/admin/intervencijas/' + i.id + '/edit" target="_blank" style="flex:1; background: ' + boja + '; color: white; padding: 12px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 800; text-align: center;">🎯 Vodi intervenciju</a>' +
                        '</div>' +
                    '</div>';
                };

                const renderTimPanel = (t) => {
                    const status = (t.status || '').replace(/_/g, ' ').toUpperCase();
                    const statusOpis = {
                        'POLAZAK': 'Tim je krenuo iz bazne postrojbe prema intervenciji',
                        'NA MJESTU': 'Tim je na lokaciji intervencije i radi',
                        'POVRATAK': 'Tim se vraća u baznu postrojbu',
                    }[status] || '';
                    
                    return '<div style="display:flex; flex-direction:column; height:100%;">' +
                        '<div style="padding: 18px 22px; background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%); color: white; position: relative;">' +
                            '<button onclick="window._fireopsClosePanel()" style="position:absolute; top:14px; right:14px; background:rgba(0,0,0,0.25); color:white; border:none; width:32px; height:32px; border-radius:50%; cursor:pointer; font-size:16px; font-weight:800;">✕</button>' +
                            '<div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.9; margin-bottom: 4px;">🚒 TIM • ' + escapeHtml(status) + '</div>' +
                            '<div style="font-size: 22px; font-weight: 900; line-height: 1.2;">' + escapeHtml(t.naziv) + '</div>' +
                            (statusOpis ? '<div style="font-size: 11px; opacity: 0.9; margin-top: 6px; font-style: italic;">' + escapeHtml(statusOpis) + '</div>' : '') +
                        '</div>' +
                        '<div style="padding: 18px 22px; flex: 1;">' +
                            '<div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">👑 ZAPOVJEDNIK</div>' +
                            '<div style="font-size: 15px; font-weight: 700; color: #111827;">' + escapeHtml(t.zapovjednik) + '</div>' +
                            '<div style="border-top: 1px solid #E5E7EB; margin: 18px 0;"></div>' +
                            '<div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">🧑‍🚒 ČLANOVI TIMA</div>' +
                            '<div style="font-size: 24px; font-weight: 900; color: #3B82F6;">' + t.brojClanova + '</div>' +
                            '<div style="border-top: 1px solid #E5E7EB; margin: 18px 0;"></div>' +
                            '<div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">🏠 BAZNA POSTROJBA</div>' +
                            '<div style="font-size: 14px; font-weight: 700; color: #111827;">' + escapeHtml(t.bazaPostrojba || '—') + '</div>' +
                            '<div style="border-top: 1px solid #E5E7EB; margin: 18px 0;"></div>' +
                            '<div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">🔥 INTERVENCIJA</div>' +
                            '<div style="font-size: 14px; font-weight: 700; color: #111827;">' + escapeHtml(t.intervencija || '—') + '</div>' +
                        '</div>' +
                        '<div style="padding: 14px 22px; background: #F9FAFB; border-top: 1px solid #E5E7EB; display: flex; flex-direction: column; gap: 8px;">' +
                            (t.intervencijaId ? '<a href="/admin/intervencijas/' + t.intervencijaId + '/edit" target="_blank" style="background: #DC2626; color: white; padding: 14px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 800; text-align: center;">🎯 Vodi intervenciju</a>' : '') +
                            '<a href="/admin/tims/' + t.id + '/edit" target="_blank" style="background: #3B82F6; color: white; padding: 12px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 800; text-align: center;">⚙ Upravljaj timom</a>' +
                        '</div>' +
                    '</div>';
                };

                const renderPostrojbaPanel = (p) => {
                    const boja = postrojbaBoja[p.tip] || postrojbaBoja['ostalo'];
                    return '<div style="display:flex; flex-direction:column; height:100%;">' +
                        '<div style="padding: 18px 22px; background: linear-gradient(135deg, ' + boja + ' 0%, ' + boja + 'CC 100%); color: white; position: relative;">' +
                            '<button onclick="window._fireopsClosePanel()" style="position:absolute; top:14px; right:14px; background:rgba(0,0,0,0.25); color:white; border:none; width:32px; height:32px; border-radius:50%; cursor:pointer; font-size:16px; font-weight:800;">✕</button>' +
                            '<div style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.9; margin-bottom: 4px;">🏠 ' + escapeHtml(p.tip) + '</div>' +
                            '<div style="font-size: 22px; font-weight: 900; line-height: 1.2;">' + escapeHtml(p.naziv) + '</div>' +
                        '</div>' +
                        '<div style="padding: 18px 22px; flex: 1;">' +
                            '<div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">📍 LOKACIJA</div>' +
                            '<div style="font-size: 15px; font-weight: 700; color: #111827;">' + escapeHtml(p.adresa || '—') + '</div>' +
                            '<div style="font-size: 12px; color: #6B7280; margin-top: 2px;">' + escapeHtml(p.jls) + '</div>' +
                            '<div style="border-top: 1px solid #E5E7EB; margin: 18px 0;"></div>' +
                            '<div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6B7280; letter-spacing: 0.5px; margin-bottom: 4px;">OPERATIVNA SPREMNOST</div>' +
                            '<div style="font-size: 14px; font-weight: 700; color: ' + (p.operativna ? '#059669' : '#DC2626') + ';">' +
                                (p.operativna ? '✓ Operativno spremna' : '⚠ Nije operativna') +
                            '</div>' +
                        '</div>' +
                    '</div>';
                };

                const layeri = { postrojbe: null, dojave: null, intervencije: null, timovi: null };
                const vidljivost = { postrojbe: false, dojave: true, intervencije: true, timovi: true };

                const popuniSlojeve = window._fireopsPopuniSlojeve = (parsed, map) => {
                    Object.keys(layeri).forEach(kljuc => {
                        if (layeri[kljuc]) map.removeLayer(layeri[kljuc]);
                        layeri[kljuc] = L.layerGroup();
                    });

                    parsed.postrojbe.forEach(p => {
                        const boja = postrojbaBoja[p.tip] || postrojbaBoja['ostalo'];
                        const marker = L.marker([p.lat, p.lng], { icon: createCirclePin(boja, 12, 'white') });
                        marker.on('click', () => openPanel(renderPostrojbaPanel(p)));
                        layeri.postrojbe.addLayer(marker);
                    });

                    parsed.dojave.forEach(d => {
                        const boja = dojavaBoja[d.prioritet] || '#6B7280';
                        const marker = L.marker([d.lat, d.lng], { icon: createCirclePin(boja, 20, 'white') });
                        marker.on('click', () => openPanel(renderDojavaPanel(d)));
                        layeri.dojave.addLayer(marker);
                    });

                    // Set svih intervencija na koje je tim "na_mjestu"
                    const intervencijeSTimom = new Set(
                        parsed.timovi
                            .filter(t => t.status === 'na_mjestu' && t.intervencijaId)
                            .map(t => t.intervencijaId)
                    );

                    parsed.intervencije.forEach(i => {
                        // Ako ima tim na_mjestu — sakrij kvadrat intervencije (vozilo ga predstavlja)
                        if (intervencijeSTimom.has(i.id)) return;
                        
                        const boja = dojavaBoja[i.prioritet] || '#6B7280';
                        const marker = L.marker([i.lat, i.lng], { icon: createSquarePin(boja, 22) });
                        marker.on('click', () => openPanel(renderIntervencijaPanel(i)));
                        layeri.intervencije.addLayer(marker);
                    });

                    parsed.timovi.forEach(t => {
                        // Tim na_mjestu — točno na poziciji intervencije, bez offset-a
                        // Tim polazak/povratak — mali offset za vizualno odvajanje
                        const offset = t.status === 'na_mjestu' ? 0 : (t.id % 8) * 0.00008;
                        const lat = t.lat + offset;
                        const lng = t.lng + offset;
                        const marker = L.marker([lat, lng], { icon: createEmojiPin('🚒', 26) });
                        marker.on('click', () => openPanel(renderTimPanel(t)));
                        layeri.timovi.addLayer(marker);
                    });

                    Object.keys(layeri).forEach(kljuc => {
                        if (vidljivost[kljuc]) map.addLayer(layeri[kljuc]);
                    });

                    const setCnt = (id, val) => {
                        const el = document.getElementById(id);
                        if (el) el.textContent = val;
                    };
                    setCnt('fireops-cnt-dojave', parsed.dojave.length);
                    setCnt('fireops-cnt-intervencije', parsed.intervencije.length);
                    setCnt('fireops-cnt-timovi', parsed.timovi.length);
                    setCnt('fireops-cnt-postrojbe', parsed.postrojbe.length);
                    setCnt('fireops-stat-dojava', parsed.dojave.length);
                    setCnt('fireops-stat-intervencija', parsed.intervencije.length);
                    setCnt('fireops-stat-timova', parsed.timovi.length);
                    setCnt('fireops-stat-postrojbi', parsed.postrojbe.length);

                    const refreshEl = document.getElementById('fireops-last-refresh');
                    if (refreshEl) {
                        const sad = new Date();
                        refreshEl.textContent = '• osvježeno ' + sad.toLocaleTimeString('hr-HR');
                    }
                };

                const setupToggles = (map) => {
                    document.querySelectorAll('.fo-toggle').forEach(label => {
                        const checkbox = label.querySelector('input[type="checkbox"]');
                        const kljuc = label.getAttribute('data-layer');
                        if (!checkbox || !kljuc) return;
                        
                        checkbox.checked = vidljivost[kljuc];
                        if (vidljivost[kljuc]) {
                            label.classList.remove('fo-toggle-neaktivan');
                            label.classList.add('fo-toggle-aktivan');
                        } else {
                            label.classList.remove('fo-toggle-aktivan');
                            label.classList.add('fo-toggle-neaktivan');
                        }

                        checkbox.addEventListener('change', () => {
                            const aktivan = checkbox.checked;
                            vidljivost[kljuc] = aktivan;
                            
                            if (aktivan) {
                                label.classList.remove('fo-toggle-neaktivan');
                                label.classList.add('fo-toggle-aktivan');
                                if (layeri[kljuc] && !map.hasLayer(layeri[kljuc])) {
                                    map.addLayer(layeri[kljuc]);
                                }
                            } else {
                                label.classList.remove('fo-toggle-aktivan');
                                label.classList.add('fo-toggle-neaktivan');
                                if (layeri[kljuc] && map.hasLayer(layeri[kljuc])) {
                                    map.removeLayer(layeri[kljuc]);
                                }
                            }
                        });
                    });
                };

                const initMap = () => {
                    const mapEl = document.getElementById('fireops-fullmap');
                    if (!mapEl || mapEl._leaflet_id) return;

                    const dataStr = mapEl.getAttribute('data-mapa-init');
                    if (!dataStr) return;

                    let parsed;
                    try { parsed = JSON.parse(dataStr); } catch (e) { return; }

                    const map = L.map('fireops-fullmap', { zoomControl: true }).setView([45.35, 17.65], 10);
                    window._fireopsFullMapMap = map;

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap',
                        maxZoom: 18,
                    }).addTo(map);

                    popuniSlojeve(parsed, map);
                    setupToggles(map);

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

                    const autoRefresh = () => {
                        fetch(window.location.pathname, {
                            headers: { 'Accept': 'text/html', 'X-Requested-With': 'XMLHttpRequest' },
                            credentials: 'same-origin',
                        })
                        .then(r => r.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const noviPodaci = doc.getElementById('fireops-mapa-podaci');
                            if (!noviPodaci) return;
                            const noviDataStr = noviPodaci.getAttribute('data-mapa');
                            if (!noviDataStr) return;
                            try {
                                const noviParsed = JSON.parse(noviDataStr);
                                popuniSlojeve(noviParsed, map);
                            } catch (e) {
                                console.warn('FireOps refresh parse:', e);
                            }
                        })
                        .catch(err => console.warn('FireOps auto-refresh:', err));
                    };
                    setInterval(autoRefresh, 30000);
                };

                window.addEventListener('storage', (e) => {
                    if (e.key !== 'fireops:zoom-mapa' || !e.newValue) return;
                    try {
                        const data = JSON.parse(e.newValue);
                        const map = window._fireopsFullMapMap;
                        if (!map) return;
                        map.flyTo([data.lat, data.lng], 15, { duration: 1.2 });
                    } catch (err) {}
                });

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
