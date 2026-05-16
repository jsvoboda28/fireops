@php
    $jeUredjivanje = !empty($urediDojavaId);
@endphp

<div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.7); z-index: 5000; display: flex; align-items: center; justify-content: center; padding: 16px; backdrop-filter: blur(4px);"
     wire:click.self="zatvoriModal">
    <div style="background: white; border-radius: 14px; box-shadow: 0 24px 48px rgba(0,0,0,0.3); width: 100%; max-width: 1100px; max-height: 95vh; overflow: hidden; display: flex; flex-direction: column;">
        
        <div style="padding: 14px 20px; background: linear-gradient(135deg, {{ $jeUredjivanje ? '#3B82F6 0%, #1E40AF' : '#EF4444 0%, #B91C1C' }} 100%); color: white; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;">
            <div>
                <div style="font-size: 9px; font-weight: 700; opacity: 0.9; text-transform: uppercase; letter-spacing: 1px;">
                    {{ $jeUredjivanje ? 'UREĐIVANJE DOJAVE' : 'NOVA DOJAVA' }}
                </div>
                <div style="font-size: 18px; font-weight: 900; margin-top: 2px;">
                    {{ $jeUredjivanje ? '✏ Uredi dojavu' : '🚨 Zaprimanje dojave' }}
                </div>
            </div>
            <button wire:click="zatvoriModal" style="background: rgba(0,0,0,0.25); color: white; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 14px; font-weight: 800;">✕</button>
        </div>
        
        <div style="padding: 16px 20px; overflow-y: auto; flex: 1; display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
                
                <div>
                    <div style="font-size: 11px; font-weight: 800; color: #475569; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">📋 Tip nepogode</div>
                    <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 4px;">
                        @php
                            $tipovi = [
                                'pozar' => ['🔥', 'Požar'],
                                'olujno_nevrijeme' => ['⛈', 'Olujno'],
                                'poplava' => ['🌊', 'Poplava'],
                                'snijeg_led' => ['❄', 'Snijeg'],
                                'klizište' => ['⛰', 'Klizište'],
                                'tuca' => ['🧊', 'Tuča'],
                                'potres' => ['🌍', 'Potres'],
                                'spasavanje' => ['⛑', 'Spašavanje'],
                                'opasne_tvari' => ['☣', 'Opasne tvari'],
                                'ostalo' => ['⚠', 'Ostalo'],
                            ];
                        @endphp
                        @foreach($tipovi as $key => $info)
                            <button type="button"
                                    wire:click="$set('novaDojavaTip', '{{ $key }}')"
                                    style="background: {{ $novaDojavaTip === $key ? '#0F172A' : 'white' }}; color: {{ $novaDojavaTip === $key ? 'white' : '#475569' }}; border: 1px solid {{ $novaDojavaTip === $key ? '#0F172A' : '#E2E8F0' }}; padding: 7px 3px; border-radius: 6px; font-size: 9px; font-weight: 700; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px;">
                                <span style="font-size: 14px;">{{ $info[0] }}</span>
                                <span>{{ $info[1] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
                
                <div>
                    <div style="font-size: 11px; font-weight: 800; color: #475569; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                        🏘 Naselje
                        @if($novaDojavaNaseljeNaziv)
                            <button wire:click="poniStavkuNaselje" style="float: right; background: transparent; border: none; color: #DC2626; cursor: pointer; font-size: 10px; font-weight: 700;">✕ Promijeni</button>
                        @endif
                    </div>
                    
                    @if($novaDojavaNaseljeNaziv)
                        <div style="background: #D1FAE5; border: 1px solid #10B981; padding: 9px 12px; border-radius: 8px; display: flex; align-items: center; gap: 6px;">
                            <span style="font-size: 16px;">✅</span>
                            <span style="font-size: 13px; font-weight: 800; color: #065F46;">{{ $novaDojavaNaseljeNaziv }}</span>
                        </div>
                    @else
                        <input type="text"
                               wire:model.live.debounce.250ms="novaDojavaNaseljePretraga"
                               placeholder="Upiši naziv naselja (min. 2 slova)..."
                               style="background: white; border: 1px solid #E2E8F0; border-radius: 8px; padding: 9px 12px; font-size: 13px; color: #0F172A; width: 100%; outline: none;">
                        
                        @if(strlen(trim($novaDojavaNaseljePretraga)) >= 2)
                            <div style="max-height: 200px; overflow-y: auto; margin-top: 4px; border: 1px solid #E2E8F0; border-radius: 6px; background: white;">
                                @forelse($this->naseljaRezultati as $naselje)
                                    <button type="button"
                                            wire:click="odaberiNaseljeUDojavi({{ $naselje['id'] }})"
                                            style="width: 100%; text-align: left; background: white; border: none; border-bottom: 1px solid #F1F5F9; padding: 8px 12px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                                        <span style="font-size: 14px;">🏘</span>
                                        <div style="flex: 1;">
                                            <div style="font-size: 12px; font-weight: 700; color: #0F172A;">{{ $naselje['naziv'] }}</div>
                                            <div style="font-size: 10px; color: #64748B;">{{ $naselje['jls'] }}@if($naselje['postanski_broj']) • {{ $naselje['postanski_broj'] }}@endif</div>
                                        </div>
                                    </button>
                                @empty
                                    <div style="padding: 16px; text-align: center; color: #94A3B8; font-size: 11px;">Nema rezultata</div>
                                @endforelse
                            </div>
                        @endif
                    @endif
                </div>
                
                @if($novaDojavaNaseljeId)
                <div>
                    <div style="font-size: 11px; font-weight: 800; color: #475569; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                        🛣 Ulica
                        @if($novaDojavaUlicaNaziv || $novaDojavaBezUlice)
                            <button wire:click="poniStavkuUlica" style="float: right; background: transparent; border: none; color: #DC2626; cursor: pointer; font-size: 10px; font-weight: 700;">✕ Promijeni</button>
                        @endif
                    </div>
                    
                    @if($novaDojavaUlicaNaziv)
                        <div style="background: #D1FAE5; border: 1px solid #10B981; padding: 9px 12px; border-radius: 8px; display: flex; align-items: center; gap: 6px;">
                            <span style="font-size: 16px;">✅</span>
                            <span style="font-size: 13px; font-weight: 800; color: #065F46;">{{ $novaDojavaUlicaNaziv }}</span>
                        </div>
                    @elseif($novaDojavaBezUlice)
                        <div style="background: #FEF3C7; border: 1px solid #F59E0B; padding: 9px 12px; border-radius: 8px; display: flex; align-items: center; gap: 6px;">
                            <span style="font-size: 16px;">⚠</span>
                            <span style="font-size: 12px; font-weight: 700; color: #92400E;">Bez ulice — koristi mapu za točnu lokaciju</span>
                        </div>
                    @else
                        <input type="text"
                               wire:model.live.debounce.250ms="novaDojavaUlicaPretraga"
                               placeholder="Upiši ulicu ili ostavi prazno za sve..."
                               style="background: white; border: 1px solid #E2E8F0; border-radius: 8px; padding: 9px 12px; font-size: 13px; color: #0F172A; width: 100%; outline: none; margin-bottom: 4px;">
                        
                        <div style="max-height: 180px; overflow-y: auto; border: 1px solid #E2E8F0; border-radius: 6px; background: white;">
                            @forelse($this->uliceRezultati as $id => $naziv)
                                <button type="button"
                                        wire:click="odaberiUlicuUDojavi({{ $id }})"
                                        style="width: 100%; text-align: left; background: white; border: none; border-bottom: 1px solid #F1F5F9; padding: 7px 12px; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                                    <span style="font-size: 12px;">🛣</span>
                                    <span style="font-size: 12px; font-weight: 600; color: #0F172A;">{{ $naziv }}</span>
                                </button>
                            @empty
                                <div style="padding: 12px; text-align: center; color: #94A3B8; font-size: 11px;">Nema ulica</div>
                            @endforelse
                        </div>
                        
                        <button wire:click="postaviBezUlice" style="margin-top: 4px; width: 100%; background: white; border: 1px dashed #94A3B8; color: #64748B; padding: 6px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer;">
                            ⚠ Bez ulice (samo naselje + mapa)
                        </button>
                    @endif
                </div>
                @endif
                
                @if($novaDojavaUlicaId)
                <div>
                    <div style="font-size: 11px; font-weight: 800; color: #475569; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                        🏠 Kućni broj
                        @if($novaDojavaKucniBroj || $novaDojavaBezKucnogBroja)
                            <button wire:click="poniStavkuKucniBroj" style="float: right; background: transparent; border: none; color: #DC2626; cursor: pointer; font-size: 10px; font-weight: 700;">✕ Promijeni</button>
                        @endif
                    </div>
                    
                    @if($novaDojavaKucniBroj)
                        <div style="background: #D1FAE5; border: 1px solid #10B981; padding: 9px 12px; border-radius: 8px; display: flex; align-items: center; gap: 6px;">
                            <span style="font-size: 16px;">✅</span>
                            <span style="font-size: 13px; font-weight: 800; color: #065F46;">Broj {{ $novaDojavaKucniBroj }}</span>
                        </div>
                    @elseif($novaDojavaBezKucnogBroja)
                        <div style="background: #FEF3C7; border: 1px solid #F59E0B; padding: 9px 12px; border-radius: 8px; display: flex; align-items: center; gap: 6px;">
                            <span style="font-size: 16px;">⚠</span>
                            <span style="font-size: 12px; font-weight: 700; color: #92400E;">Bez kućnog broja</span>
                        </div>
                    @else
                        <div style="max-height: 140px; overflow-y: auto; border: 1px solid #E2E8F0; border-radius: 6px; background: white; padding: 6px;">
                            @php $brojevi = $this->kucniBrojeviRezultati; @endphp
                            @if(count($brojevi) > 0)
                                <div style="display: grid; grid-template-columns: repeat(8, 1fr); gap: 3px;">
                                    @foreach($brojevi as $kb)
                                        <button type="button"
                                                wire:click="odaberiKucniBrojUDojavi({{ $kb['id'] }})"
                                                style="background: white; border: 1px solid #E2E8F0; padding: 5px 2px; border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer; color: #475569;"
                                                title="{{ $kb['broj'] }}">
                                            {{ $kb['broj'] }}
                                        </button>
                                    @endforeach
                                </div>
                            @else
                                <div style="padding: 8px; text-align: center; color: #94A3B8; font-size: 11px;">Nema kućnih brojeva u bazi</div>
                            @endif
                        </div>
                        
                        <button wire:click="postaviBezKucnogBroja" style="margin-top: 4px; width: 100%; background: white; border: 1px dashed #94A3B8; color: #64748B; padding: 6px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer;">
                            ⚠ Bez kućnog broja
                        </button>
                    @endif
                </div>
                @endif
                
                <div>
                    <div style="font-size: 11px; font-weight: 800; color: #475569; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">🎯 Prioritet</div>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px;">
                        @php
                            $prioOpcije = [
                                'kriticna' => ['🔴', 'KRITIČNA', '#DC2626'],
                                'visoka' => ['🟡', 'VISOKA', '#F59E0B'],
                                'standardna' => ['🟢', 'STANDARDNA', '#10B981'],
                            ];
                        @endphp
                        @foreach($prioOpcije as $key => $info)
                            <button type="button"
                                    wire:click="$set('novaDojavaPrioritet', '{{ $key }}')"
                                    style="background: {{ $novaDojavaPrioritet === $key ? $info[2] : 'white' }}; color: {{ $novaDojavaPrioritet === $key ? 'white' : '#475569' }}; border: 2px solid {{ $novaDojavaPrioritet === $key ? $info[2] : '#E2E8F0' }}; padding: 8px 6px; border-radius: 8px; cursor: pointer; text-align: center;">
                                <div style="font-size: 14px; margin-bottom: 2px;">{{ $info[0] }}</div>
                                <div style="font-size: 10px; font-weight: 800; letter-spacing: 0.3px;">{{ $info[1] }}</div>
                            </button>
                        @endforeach
                    </div>
                </div>
                
                @if($jeUredjivanje)
                <div>
                    <div style="font-size: 11px; font-weight: 800; color: #475569; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">📍 Status dojave</div>
                    <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 3px;">
                        @php
                            $statusi = [
                                'zaprimljena' => ['📥', 'Zaprimljena'],
                                'dodijeljena' => ['📋', 'Dodijeljena'],
                                'u_tijeku' => ['⏳', 'U tijeku'],
                                'rijesena' => ['✅', 'Riješena'],
                                'otkazana' => ['❌', 'Otkazana'],
                            ];
                        @endphp
                        @foreach($statusi as $key => $info)
                            <button type="button"
                                    wire:click="$set('novaDojavaStatus', '{{ $key }}')"
                                    style="background: {{ $novaDojavaStatus === $key ? '#0F172A' : 'white' }}; color: {{ $novaDojavaStatus === $key ? 'white' : '#475569' }}; border: 1px solid {{ $novaDojavaStatus === $key ? '#0F172A' : '#E2E8F0' }}; padding: 6px 3px; border-radius: 5px; font-size: 9px; font-weight: 700; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px;">
                                <span style="font-size: 12px;">{{ $info[0] }}</span>
                                <span>{{ $info[1] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <div>
                    <div style="font-size: 11px; font-weight: 800; color: #475569; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">📝 Detalji / opis</div>
                    <textarea wire:model="novaDojavaOpis"
                              rows="2"
                              placeholder="npr. Plinska boca, dvoje djece u kući..."
                              style="background: white; border: 1px solid #E2E8F0; border-radius: 8px; padding: 8px 12px; font-size: 12px; color: #0F172A; width: 100%; outline: none; resize: vertical; font-family: inherit;"></textarea>
                </div>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <div style="font-size: 11px; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; justify-content: space-between;">
                    <span>🗺 Lokacija na karti</span>
                    @if($novaDojavaLatitude && $novaDojavaLongitude)
                        <span style="font-size: 10px; color: #10B981; font-weight: 700;">📍 {{ number_format($novaDojavaLatitude, 5) }}, {{ number_format($novaDojavaLongitude, 5) }}</span>
                    @else
                        <span style="font-size: 10px; color: #94A3B8; font-weight: 600;">Bez GPS — klikni na mapu</span>
                    @endif
                </div>
                
                <div wire:ignore style="flex: 1; min-height: 480px; background: #1E40AF; border-radius: 10px; overflow: hidden; border: 1px solid #E2E8F0; position: relative;">
                    <div id="fo-dojava-mapa" style="width: 100%; height: 100%; min-height: 480px;"></div>
                    <div style="position: absolute; top: 8px; left: 8px; background: rgba(255,255,255,0.95); padding: 4px 8px; border-radius: 5px; font-size: 10px; font-weight: 800; color: #0F172A; text-transform: uppercase; letter-spacing: 0.5px; z-index: 1000; pointer-events: none;">
                        🛰 Satelit · klik za pin
                    </div>
                </div>
            </div>
        </div>
        
        <div style="padding: 12px 20px; background: #F8FAFC; border-top: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center; gap: 8px; flex-shrink: 0;">
            <div style="font-size: 11px; color: #64748B;">
                @if($novaDojavaNaseljeNaziv)
                    <strong style="color: #0F172A;">Adresa:</strong> 
                    @if($novaDojavaUlicaNaziv){{ $novaDojavaUlicaNaziv }}@endif
                    @if($novaDojavaKucniBroj) {{ $novaDojavaKucniBroj }}@endif
                    @if($novaDojavaUlicaNaziv), @endif{{ $novaDojavaNaseljeNaziv }}
                @else
                    <span style="color: #94A3B8;">Odaberi naselje za nastavak</span>
                @endif
            </div>
            <div style="display: flex; gap: 8px;">
                <button wire:click="zatvoriModal" style="background: white; border: 1px solid #E2E8F0; color: #475569; padding: 9px 18px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer;">
                    Odustani
                </button>
                @if($jeUredjivanje)
                    <button wire:click="azurirajDojavu"
                            @if(!$novaDojavaNaseljeId || (!$novaDojavaUlicaId && !$novaDojavaBezUlice)) disabled @endif
                            style="background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%); color: white; border: none; padding: 9px 22px; border-radius: 8px; font-size: 12px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); opacity: {{ (!$novaDojavaNaseljeId || (!$novaDojavaUlicaId && !$novaDojavaBezUlice)) ? '0.4' : '1' }};">
                        💾 Spremi promjene
                    </button>
                @else
                    <button wire:click="kreirajNovuDojavu"
                            @if(!$novaDojavaNaseljeId || (!$novaDojavaUlicaId && !$novaDojavaBezUlice)) disabled @endif
                            style="background: linear-gradient(135deg, #EF4444 0%, #B91C1C 100%); color: white; border: none; padding: 9px 22px; border-radius: 8px; font-size: 12px; font-weight: 800; cursor: pointer; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); opacity: {{ (!$novaDojavaNaseljeId || (!$novaDojavaUlicaId && !$novaDojavaBezUlice)) ? '0.4' : '1' }};">
                        🚨 Zaprimi dojavu
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    if (typeof L === 'undefined') {
        setTimeout(arguments.callee, 200);
        return;
    }
    
    if (window._foDojavaMapa) {
        try { window._foDojavaMapa.remove(); } catch(e) {}
        window._foDojavaMapa = null;
    }
    
    setTimeout(() => {
        const el = document.getElementById('fo-dojava-mapa');
        if (!el || window._foDojavaMapa) return;
        
        const lat = {{ $novaDojavaLatitude ?? 45.3 }};
        const lng = {{ $novaDojavaLongitude ?? 17.5 }};
        const zoom = {{ $novaDojavaLatitude ? 17 : 9 }};
        
        const mapa = L.map(el, { zoomControl: true, attributionControl: false }).setView([lat, lng], zoom);
        window._foDojavaMapa = mapa;
        
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
        }).addTo(mapa);
        
        let pin = null;
        
        @if($novaDojavaLatitude && $novaDojavaLongitude)
            const pinIcon = L.divIcon({
                className: 'fo-dojava-pin',
                html: '<div style="width:24px;height:24px;background:#DC2626;border:3px solid white;border-radius:50%;box-shadow:0 0 0 2px #DC2626, 0 4px 12px rgba(0,0,0,0.6);"></div>',
                iconSize: [30, 30],
                iconAnchor: [15, 15],
            });
            pin = L.marker([{{ $novaDojavaLatitude }}, {{ $novaDojavaLongitude }}], { icon: pinIcon, draggable: true }).addTo(mapa);
            
            pin.on('dragend', (e) => {
                const p = e.target.getLatLng();
                @this.call('postaviKoordinate', p.lat, p.lng);
            });
        @endif
        
        mapa.on('click', (e) => {
            const p = e.latlng;
            
            if (pin) {
                pin.setLatLng(p);
            } else {
                const pinIcon = L.divIcon({
                    className: 'fo-dojava-pin',
                    html: '<div style="width:24px;height:24px;background:#DC2626;border:3px solid white;border-radius:50%;box-shadow:0 0 0 2px #DC2626, 0 4px 12px rgba(0,0,0,0.6);"></div>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 15],
                });
                pin = L.marker(p, { icon: pinIcon, draggable: true }).addTo(mapa);
                
                pin.on('dragend', (ev) => {
                    const pp = ev.target.getLatLng();
                    @this.call('postaviKoordinate', pp.lat, pp.lng);
                });
            }
            
            @this.call('postaviKoordinate', p.lat, p.lng);
        });
    }, 200);
})();
</script>
