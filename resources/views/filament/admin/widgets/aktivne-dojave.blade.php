<x-filament-widgets::widget>
    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #E5E7EB;">
        
        <!-- Naslov sekcije -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 2px solid #F3F4F6;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="background: linear-gradient(135deg, #F97316 0%, #DC2626 100%); padding: 10px; border-radius: 10px; color: white;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                        <path fill-rule="evenodd" d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <div style="font-size: 18px; font-weight: 800; color: #111827;">Aktivne dojave</div>
                    <div style="font-size: 12px; color: #6B7280; margin-top: 2px;">
                        @if($ukupno > 0)
                            {{ $ukupno }} {{ $ukupno == 1 ? 'aktivna dojava' : ($ukupno < 5 ? 'aktivne dojave' : 'aktivnih dojava') }}
                        @else
                            Nema aktivnih dojava
                        @endif
                    </div>
                </div>
            </div>
            
            @if($ukupno > 0)
                <a href="{{ route('filament.admin.resources.dojavas.index') }}" 
                   style="font-size: 13px; color: #DC2626; font-weight: 600; text-decoration: none; padding: 8px 16px; border-radius: 8px; background: #FEF2F2; transition: all 0.2s;"
                   onmouseover="this.style.background='#FEE2E2'"
                   onmouseout="this.style.background='#FEF2F2'">
                    Sve dojave →
                </a>
            @endif
        </div>

        @if($dojave->isEmpty())
            <!-- Prazno stanje -->
            <div style="padding: 60px 20px; text-align: center;">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background: #F0FDF4; border-radius: 50%; margin-bottom: 16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#059669" width="32" height="32">
                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 4px;">Nema aktivnih dojava</div>
                <div style="font-size: 14px; color: #6B7280;">Sustav je u redovnom stanju.</div>
            </div>
        @else
            <!-- Lista dojava -->
            <div style="display: grid; gap: 12px;">
                @foreach($dojave as $dojava)
                    @php
                        $prioritetStyles = match($dojava->prioritet) {
                            'kriticna' => ['bg' => '#FEF2F2', 'border' => '#DC2626', 'text' => '#991B1B', 'badge_bg' => '#DC2626', 'badge_text' => 'white', 'label' => '🔴 KRITIČNA'],
                            'visoka' => ['bg' => '#FFFBEB', 'border' => '#F59E0B', 'text' => '#92400E', 'badge_bg' => '#F59E0B', 'badge_text' => 'white', 'label' => '🟡 VISOKA'],
                            default => ['bg' => '#F0FDF4', 'border' => '#10B981', 'text' => '#065F46', 'badge_bg' => '#10B981', 'badge_text' => 'white', 'label' => '🟢 STANDARDNA'],
                        };

                        $statusLabel = match($dojava->status) {
                            'zaprimljena' => 'ZAPRIMLJENA',
                            'dodijeljena' => 'DODIJELJENA',
                            'u_tijeku' => 'U TIJEKU',
                            default => strtoupper($dojava->status),
                        };

                        $statusBg = match($dojava->status) {
                            'zaprimljena' => '#E5E7EB',
                            'dodijeljena' => '#DBEAFE',
                            'u_tijeku' => '#FEF3C7',
                            default => '#E5E7EB',
                        };

                        $tipLabel = match($dojava->tip_nepogode) {
                            'olujno_nevrijeme' => 'Olujno nevrijeme',
                            'poplava' => 'Poplava',
                            'pozar' => 'Požar',
                            'snijeg_led' => 'Snijeg/led',
                            'klizište' => 'Klizište',
                            'tuca' => 'Tuča',
                            'potres' => 'Potres',
                            default => 'Ostalo',
                        };

                        $proteklo = $dojava->vrijeme_zaprimanja->diffForHumans(null, true);
                    @endphp

                    <a href="{{ route('filament.admin.resources.dojavas.edit', $dojava) }}"
                       style="text-decoration: none; color: inherit; display: block; background: {{ $prioritetStyles['bg'] }}; border-left: 5px solid {{ $prioritetStyles['border'] }}; border-radius: 8px; padding: 16px 20px; transition: transform 0.15s, box-shadow 0.15s;"
                       onmouseover="this.style.transform='translateX(4px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';"
                       onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='none';">
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                            
                            <!-- Lijevo: glavni podaci -->
                            <div style="flex: 1; min-width: 0;">
                                <!-- Header: broj + prioritet + status -->
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; flex-wrap: wrap;">
                                    <span style="font-size: 14px; font-weight: 800; color: {{ $prioritetStyles['text'] }};">
                                        #{{ $dojava->broj_dojave }}
                                    </span>
                                    <span style="font-size: 10px; font-weight: 800; letter-spacing: 0.5px; background: {{ $prioritetStyles['badge_bg'] }}; color: {{ $prioritetStyles['badge_text'] }}; padding: 3px 8px; border-radius: 4px;">
                                        {{ $prioritetStyles['label'] }}
                                    </span>
                                    <span style="font-size: 10px; font-weight: 700; letter-spacing: 0.5px; background: {{ $statusBg }}; color: #374151; padding: 3px 8px; border-radius: 4px;">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                
                                <!-- Lokacija -->
                                <div style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 4px;">
                                    {{ $dojava->adresa }}
                                </div>
                                
                                <!-- Meta info -->
                                <div style="font-size: 13px; color: #4B5563; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <span style="font-weight: 600;">{{ $dojava->jls?->naziv ?? 'Bez JLS' }}</span>
                                    <span style="opacity: 0.5;">•</span>
                                    <span>{{ $tipLabel }}</span>
                                    @if($dojava->ugrozenost_ljudi === 'da')
                                        <span style="opacity: 0.5;">•</span>
                                        <span style="color: #DC2626; font-weight: 700;">⚠ Ugroženi ljudi</span>
                                    @endif
                                </div>
                                
                                <!-- Opis -->
                                @if($dojava->opis)
                                    <div style="font-size: 13px; color: #6B7280; margin-top: 6px; line-height: 1.4;">
                                        {{ \Illuminate\Support\Str::limit($dojava->opis, 100) }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Desno: vrijeme + brojač -->
                            <div style="text-align: right; flex-shrink: 0;">
                                <div style="font-size: 11px; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                    Otvoreno prije
                                </div>
                                <div style="font-size: 18px; font-weight: 800; color: {{ $prioritetStyles['text'] }}; margin-top: 2px;">
                                    {{ $proteklo }}
                                </div>
                                <div style="font-size: 11px; color: #9CA3AF; margin-top: 4px; font-variant-numeric: tabular-nums;">
                                    {{ $dojava->vrijeme_zaprimanja->format('d.m. H:i') }}
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-widgets::widget>