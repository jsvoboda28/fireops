<x-filament-panels::page>
    
    {{-- DISPEČERSKI MONITOR — 3 KOLONE LAYOUT --}}
    <div style="display: grid; grid-template-columns: 320px 1fr 360px; gap: 16px; height: calc(100vh - 200px); min-height: 600px;">

        {{-- ===== LIJEVO: AKTIVNE DOJAVE + DOGAĐAJI ===== --}}
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
                
                <div style="overflow-y: auto; flex: 1; display: flex; flex-direction: column; gap: 6px;">
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
                        <a href="{{ route('filament.admin.resources.dojavas.edit', $dojava) }}"
                           style="text-decoration: none; color: inherit; display: block; background: {{ $prio['bg'] }}; border-left: 4px solid {{ $prio['border'] }}; border-radius: 6px; padding: 10px 12px;">
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
                        </a>
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

        {{-- ===== SREDINA: MAPA (placeholder za sad) ===== --}}
        <div style="background: linear-gradient(135deg, #1E40AF 0%, #0F172A 100%); border-radius: 12px; padding: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; flex-direction: column; overflow: hidden; position: relative;">
            
            {{-- Header mape --}}
            <div style="position: absolute; top: 0; left: 0; right: 0; padding: 14px 20px; background: linear-gradient(to bottom, rgba(0,0,0,0.6), transparent); color: white; z-index: 10;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.9;">FireOps PSŽ</div>
                        <div style="font-size: 18px; font-weight: 800; margin-top: 2px;">Operativna karta</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 11px; opacity: 0.9;">Stanje sustava</div>
                        <div style="font-size: 16px; font-weight: 800; color: {{ $stanje['boja'] === '#DC2626' ? '#FCA5A5' : ($stanje['boja'] === '#F97316' ? '#FED7AA' : '#86EFAC') }};">
                            {{ $stanje['naslov'] }}
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Placeholder za mapu --}}
            <div style="flex: 1; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.5);">
                <div style="text-align: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="80" height="80" style="margin: 0 auto; opacity: 0.4;">
                        <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                    </svg>
                    <div style="font-size: 18px; font-weight: 700; margin-top: 12px;">Operativna karta PSŽ</div>
                    <div style="font-size: 13px; opacity: 0.7; margin-top: 6px; max-width: 320px;">Mapa s pinovima dojava i postrojbi dolazi u sljedećoj fazi razvoja</div>
                </div>
            </div>
        </div>

        {{-- ===== DESNO: DETALJI ===== --}}
        <div style="background: white; border-radius: 12px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; display: flex; flex-direction: column;">
            <div style="padding-bottom: 12px; border-bottom: 2px solid #F3F4F6; margin-bottom: 12px;">
                <div style="font-size: 14px; font-weight: 800; color: #111827;">📋 Detalji</div>
                <div style="font-size: 11px; color: #6B7280; margin-top: 2px;">Odaberite dojavu ili događaj</div>
            </div>
            
            <div style="flex: 1; display: flex; align-items: center; justify-content: center; color: #9CA3AF;">
                <div style="text-align: center; padding: 20px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="48" height="48" style="margin: 0 auto; opacity: 0.4;">
                        <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" clip-rule="evenodd" />
                    </svg>
                    <div style="font-size: 13px; margin-top: 12px; max-width: 200px;">Klikni na dojavu lijevo da vidiš detalje i poduzmeš akciju</div>
                </div>
            </div>
        </div>

    </div>

</x-filament-panels::page>