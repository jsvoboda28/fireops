<x-filament-panels::page>
    <div wire:ignore.self>
        <style>
            body, html { overflow: hidden !important; }
            .fi-main { padding: 0 !important; max-width: 100% !important; }
            .fi-main-ctn { padding: 0 !important; }
            section.fi-main { padding: 0 !important; }
            .fi-page { padding: 0 !important; }
            .fi-page-header-main-ctn { padding: 8px 16px !important; }
            .fo-scroll::-webkit-scrollbar { width: 6px; }
            .fo-scroll::-webkit-scrollbar-track { background: #F3F4F6; }
            .fo-scroll::-webkit-scrollbar-thumb { background: #D1D5DB; border-radius: 3px; }
            .fo-card-hover { transition: all 0.15s; cursor: pointer; }
            .fo-card-hover:hover { transform: translateX(3px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        </style>

        <div style="position: fixed; top: 64px; left: 0; right: 0; bottom: 0; background: #F9FAFB; display: flex; flex-direction: column;">
            
            {{-- ===== TOP TRAKA ===== --}}
            <div style="background: white; padding: 10px 20px; border-bottom: 1px solid #E5E7EB; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-shrink: 0;">
                
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="background: {{ $stanje['boja'] }}; color: white; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 800; letter-spacing: 0.5px;">
                        ● {{ $stanje['naslov'] }}
                    </div>
                    <div style="display: flex; gap: 14px; font-size: 12px; color: #6B7280;">
                        <span><strong style="color: #111827; font-size: 14px;">{{ $brojDojava }}</strong> dojava</span>
                        <span><strong style="color: #111827; font-size: 14px;">{{ $brojIntervencija }}</strong> intervencija</span>
                        <span><strong style="color: #111827; font-size: 14px;">{{ $brojTimova }}</strong> aktivnih timova</span>
                    </div>
                </div>

                <div style="display: flex; gap: 8px; align-items: center;">
                    <span id="fireops-clock" style="font-size: 13px; color: #6B7280; font-variant-numeric: tabular-nums; font-weight: 600;">--:--:--</span>
                    
                    <a href="/admin/operativa" target="_blank"
                       style="background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%); color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 800; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);">
                        🗺 Operativna karta
                    </a>
                    
                    <a href="/admin/dojavas/create"
                       style="background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%); color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 800; display: flex; align-items: center; gap: 6px; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);">
                        ➕ Nova dojava
                    </a>
                </div>
            </div>

            {{-- ===== 3 KOLONE — full height ===== --}}
            <div style="display: grid; grid-template-columns: 1fr 1.4fr 1fr; gap: 12px; padding: 12px; flex: 1; min-height: 0; overflow: hidden;">
                
                {{-- ===== LIJEVO: DOJAVE ===== --}}
                <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); border: 1px solid #E5E7EB; display: flex; flex-direction: column; min-height: 0; overflow: hidden;">
                    <div style="padding: 12px 16px; border-bottom: 1px solid #F3F4F6; flex-shrink: 0; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span style="font-size: 14px; font-weight: 800; color: #111827;">📞 Dojave</span>
                            <span style="font-size: 11px; font-weight: 700; background: #FEE2E2; color: #991B1B; padding: 2px 8px; border-radius: 10px;">{{ $brojDojava }}</span>
                        </div>
                    </div>
                    
                    <div class="fo-scroll" style="overflow-y: auto; flex: 1; padding: 8px;">
                        @forelse($dojave as $dojava)
                            @php
                                $prio = match($dojava->prioritet) {
                                    'kriticna' => ['bg' => '#FEE2E2', 'border' => '#DC2626', 'text' => '#991B1B', 'badge' => 'KRIT.'],
                                    'visoka' => ['bg' => '#FEF3C7', 'border' => '#F59E0B', 'text' => '#92400E', 'badge' => 'VIS.'],
                                    default => ['bg' => '#D1FAE5', 'border' => '#10B981', 'text' => '#065F46', 'badge' => 'STD.'],
                                };
                                $tip = match($dojava->tip_nepogode) {
                                    'olujno_nevrijeme' => '⛈', 'poplava' => '🌊', 'pozar' => '🔥',
                                    'snijeg_led' => '❄', 'klizište' => '⛰', 'tuca' => '🧊',
                                    'potres' => '🌍', 'spasavanje' => '⛑', 'opasne_tvari' => '☣',
                                    default => '❓',
                                };
                            @endphp
                            <a href="/admin/dojavas/{{ $dojava->id }}/edit"
                               class="fo-card-hover"
                               style="text-decoration: none; color: inherit; display: block; background: {{ $prio['bg'] }}; border-left: 4px solid {{ $prio['border'] }}; border-radius: 6px; padding: 10px 12px; margin-bottom: 6px;">
                                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
                                    <span style="font-size: 16px;">{{ $tip }}</span>
                                    <span style="font-size: 9px; font-weight: 800; background: {{ $prio['border'] }}; color: white; padding: 2px 6px; border-radius: 3px;">{{ $prio['badge'] }}</span>
                                    @if($dojava->intervencija)
                                        <span style="font-size: 9px; font-weight: 700; background: white; color: #DC2626; padding: 2px 6px; border-radius: 3px; border: 1px solid #FCA5A5;" title="Vezana na intervenciju">🔥 INT</span>
                                    @endif
                                    <span style="font-size: 10px; color: {{ $prio['text'] }}; font-weight: 700; margin-left: auto;">{{ $dojava->vrijeme_zaprimanja->format('H:i') }}</span>
                                </div>
                                <div style="font-size: 12px; font-weight: 700; color: #111827; line-height: 1.3;">
                                    {{ \Illuminate\Support\Str::limit($dojava->adresa, 40) }}
                                </div>
                                <div style="font-size: 11px; color: #6B7280; margin-top: 2px;">
                                    #{{ $dojava->broj_dojave }} • {{ $dojava->jls?->naziv ?? '—' }}
                                </div>
                            </a>
                        @empty
                            <div style="text-align: center; padding: 40px 10px; color: #6B7280;">
                                <div style="font-size: 36px; margin-bottom: 8px;">✓</div>
                                <div style="font-size: 13px; font-weight: 600;">Nema aktivnih dojava</div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- ===== SREDINA: INTERVENCIJE ===== --}}
                <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); border: 1px solid #E5E7EB; display: flex; flex-direction: column; min-height: 0; overflow: hidden;">
                    <div style="padding: 12px 16px; border-bottom: 1px solid #F3F4F6; flex-shrink: 0; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <span style="font-size: 14px; font-weight: 800; color: #111827;">🔥 Aktivne intervencije</span>
                            <span style="font-size: 11px; font-weight: 700; background: #FEE2E2; color: #991B1B; padding: 2px 8px; border-radius: 10px;">{{ $brojIntervencija }}</span>
                        </div>
                        <a href="/admin/intervencijas/create" style="font-size: 11px; font-weight: 700; color: #DC2626; text-decoration: none;">➕ Nova</a>
                    </div>
                    
                    <div class="fo-scroll" style="overflow-y: auto; flex: 1; padding: 8px;">
                        @forelse($intervencije as $int)
                            @php
                                $prio = match($int->prioritet) {
                                    'kriticna' => ['bg' => '#FEE2E2', 'border' => '#DC2626', 'text' => '#991B1B'],
                                    'visoka' => ['bg' => '#FEF3C7', 'border' => '#F59E0B', 'text' => '#92400E'],
                                    default => ['bg' => '#D1FAE5', 'border' => '#10B981', 'text' => '#065F46'],
                                };
                                $aktivniTimovi = $int->timovi->where('trenutni_status', '!=', 'raspusten');
                                $brojLjudi = $aktivniTimovi->sum(fn($t) => $t->trenutniClanovi->count());
                            @endphp
                            <a href="/admin/intervencijas/{{ $int->id }}/edit"
                               class="fo-card-hover"
                               style="text-decoration: none; color: inherit; display: block; background: white; border: 1px solid #E5E7EB; border-left: 4px solid {{ $prio['border'] }}; border-radius: 8px; padding: 12px; margin-bottom: 8px;">
                                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                                    <span style="font-size: 10px; font-weight: 800; background: {{ $prio['border'] }}; color: white; padding: 2px 7px; border-radius: 3px; text-transform: uppercase;">{{ $int->prioritet }}</span>
                                    <span style="font-size: 11px; color: #6B7280; margin-left: auto;">
                                        {{ $int->vrijeme_otvaranja->format('d.m. H:i') }}
                                    </span>
                                </div>
                                <div style="font-size: 14px; font-weight: 800; color: #111827; line-height: 1.3;">
                                    {{ \Illuminate\Support\Str::limit($int->naziv, 50) }}
                                </div>
                                <div style="font-size: 11px; color: #6B7280; margin-top: 2px;">
                                    #{{ $int->broj }} • {{ $int->jls?->naziv ?? '—' }}
                                </div>
                                
                                @if($aktivniTimovi->isNotEmpty())
                                    <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #F3F4F6; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                        <span style="font-size: 11px; font-weight: 700; color: #374151;">
                                            🚒 {{ $aktivniTimovi->count() }} tim{{ $aktivniTimovi->count() === 1 ? '' : 'ova' }}
                                        </span>
                                        <span style="font-size: 11px; color: #6B7280;">•</span>
                                        <span style="font-size: 11px; font-weight: 700; color: #374151;">
                                            👥 {{ $brojLjudi }} ljudi
                                        </span>
                                        @php
                                            $statusi = $aktivniTimovi->groupBy('trenutni_status');
                                        @endphp
                                        @foreach($statusi as $status => $timoviPoStatusu)
                                            @php
                                                $emoji = match($status) {
                                                    'na_mjestu' => '📍',
                                                    'polazak' => '🚒',
                                                    'povratak' => '↩️',
                                                    'formiran' => '🆕',
                                                    'intervencija_zavrsena' => '✅',
                                                    default => '•',
                                                };
                                            @endphp
                                            <span style="font-size: 11px; background: #F3F4F6; padding: 2px 7px; border-radius: 3px; color: #374151;">
                                                {{ $emoji }} {{ $timoviPoStatusu->count() }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </a>
                        @empty
                            <div style="text-align: center; padding: 40px 10px; color: #6B7280;">
                                <div style="font-size: 36px; margin-bottom: 8px;">🔥</div>
                                <div style="font-size: 13px; font-weight: 600;">Bez aktivnih intervencija</div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- ===== DESNO: TIMELINE ===== --}}
                <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); border: 1px solid #E5E7EB; display: flex; flex-direction: column; min-height: 0; overflow: hidden;">
                    <div style="padding: 12px 16px; border-bottom: 1px solid #F3F4F6; flex-shrink: 0; display: flex; align-items: center; gap: 6px;">
                        <span style="font-size: 14px; font-weight: 800; color: #111827;">📋 Timeline</span>
                        <span style="font-size: 11px; font-weight: 700; background: #DBEAFE; color: #1E40AF; padding: 2px 8px; border-radius: 10px;">{{ $timeline->count() }}</span>
                        <span style="font-size: 10px; color: #9CA3AF; margin-left: auto;">live</span>
                    </div>
                    
                    <div class="fo-scroll" style="overflow-y: auto; flex: 1; padding: 8px;">
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
                                    default => '#6B7280',
                                };
                            @endphp
                            <div style="border-left: 3px solid {{ $boja }}; padding: 7px 10px; background: #F9FAFB; border-radius: 4px; margin-bottom: 5px;">
                                <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 2px;">
                                    <span style="font-size: 13px;">{{ $tipIkona }}</span>
                                    <span style="font-size: 10px; font-weight: 800; color: {{ $boja }}; text-transform: uppercase;">{{ str_replace('_', ' ', $log->status) }}</span>
                                    <span style="font-size: 10px; color: #6B7280; margin-left: auto;">{{ $log->vrijeme->format('H:i') }}</span>
                                </div>
                                <div style="font-size: 11px; color: #111827; font-weight: 700;">{{ $log->tim?->naziv ?? '—' }}</div>
                                @if($log->intervencija)
                                    <div style="font-size: 10px; color: #6B7280; margin-top: 1px;">
                                        → {{ \Illuminate\Support\Str::limit($log->intervencija->naziv, 30) }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div style="text-align: center; padding: 40px 10px; color: #6B7280;">
                                <div style="font-size: 32px; margin-bottom: 8px;">📜</div>
                                <div style="font-size: 12px;">Bez aktivnosti</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                const upd = () => {
                    const el = document.getElementById('fireops-clock');
                    if (el) {
                        const d = new Date();
                        el.textContent = d.toLocaleTimeString('hr-HR');
                    }
                };
                upd();
                setInterval(upd, 1000);
            })();
        </script>
    @endpush
</x-filament-panels::page>
