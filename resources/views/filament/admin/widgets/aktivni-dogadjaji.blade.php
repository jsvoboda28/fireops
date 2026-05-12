<x-filament-widgets::widget>
    <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #E5E7EB;">
        
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 2px solid #F3F4F6;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%); padding: 10px; border-radius: 10px; color: white; display: flex; align-items: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                        <path fill-rule="evenodd" d="M12.963 2.286a.75.75 0 0 0-1.071-.136 9.742 9.742 0 0 0-3.539 6.176 7.547 7.547 0 0 1-1.705-1.715.75.75 0 0 0-1.152-.082A9 9 0 1 0 15.68 4.534a7.46 7.46 0 0 1-2.717-2.248ZM15.75 14.25a3.75 3.75 0 1 1-7.313-1.172c.628.465 1.35.81 2.133 1a5.99 5.99 0 0 1 1.925-3.546 3.75 3.75 0 0 1 3.255 3.718Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <div style="font-size: 18px; font-weight: 800; color: #111827;">Aktivni operativni događaji</div>
                    <div style="font-size: 12px; color: #6B7280; margin-top: 2px;">
                        @if($ukupno > 0)
                            {{ $ukupno }} {{ $ukupno == 1 ? 'aktivan događaj' : ($ukupno < 5 ? 'aktivna događaja' : 'aktivnih događaja') }}
                        @else
                            Bez aktivnih događaja
                        @endif
                    </div>
                </div>
            </div>
            
            @if($ukupno > 0)
                <a href="{{ route('filament.admin.resources.operativni-dogadjajs.index') }}" 
                   style="font-size: 13px; color: #DC2626; font-weight: 600; text-decoration: none; padding: 8px 16px; border-radius: 8px; background: #FEF2F2;">
                    Svi događaji →
                </a>
            @endif
        </div>

        @if($dogadjaji->isEmpty())
            <div style="padding: 60px 20px; text-align: center;">
                <div style="display: inline-flex; align-items: center; justify-content: center; width: 64px; height: 64px; background: #F0FDF4; border-radius: 50%; margin-bottom: 16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#059669" width="32" height="32">
                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 4px;">Nema aktivnih događaja</div>
                <div style="font-size: 14px; color: #6B7280;">Sustav je u redovnom stanju.</div>
            </div>
        @else
            <div style="display: grid; gap: 12px;">
                @foreach($dogadjaji as $dogadjaj)
                    @php
                        $statusStyles = match($dogadjaj->status) {
                            'aktivan' => ['bg' => '#FEF2F2', 'border' => '#DC2626', 'text' => '#991B1B', 'badge_bg' => '#DC2626', 'label' => '🔴 AKTIVAN'],
                            'pracenje' => ['bg' => '#EFF6FF', 'border' => '#3B82F6', 'text' => '#1E40AF', 'badge_bg' => '#3B82F6', 'label' => '🔵 PRAĆENJE'],
                            default => ['bg' => '#F3F4F6', 'border' => '#6B7280', 'text' => '#374151', 'badge_bg' => '#6B7280', 'label' => strtoupper($dogadjaj->status)],
                        };

                        $stupanjStyles = match($dogadjaj->stupanj_sukoba) {
                            'IV' => ['bg' => '#DC2626', 'text' => 'white', 'label' => 'STUPANJ IV'],
                            'III' => ['bg' => '#F59E0B', 'text' => 'white', 'label' => 'STUPANJ III'],
                            'II' => ['bg' => '#3B82F6', 'text' => 'white', 'label' => 'STUPANJ II'],
                            'I' => ['bg' => '#10B981', 'text' => 'white', 'label' => 'STUPANJ I'],
                            default => null,
                        };

                        $razinaLabel = match($dogadjaj->razina) {
                            'zupanijska' => 'ŽUPANIJSKA',
                            'lokalna' => 'LOKALNA',
                            default => strtoupper($dogadjaj->razina ?? ''),
                        };

                        $tipLabel = match($dogadjaj->tip_nepogode) {
                            'olujno_nevrijeme' => 'Olujno nevrijeme',
                            'poplava' => 'Poplava',
                            'pozar' => 'Požar',
                            'snijeg_led' => 'Snijeg/led',
                            'klizište' => 'Klizište',
                            'tuca' => 'Tuča',
                            'potres' => 'Potres',
                            default => 'Ostalo',
                        };

                        $trajanje = $dogadjaj->vrijeme_otvaranja->diffForHumans(null, true);
                    @endphp

                    <a href="{{ route('filament.admin.resources.operativni-dogadjajs.edit', $dogadjaj) }}"
                       style="text-decoration: none; color: inherit; display: block; background: {{ $statusStyles['bg'] }}; border-left: 5px solid {{ $statusStyles['border'] }}; border-radius: 8px; padding: 18px 22px;">
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap;">
                            
                            <div style="flex: 1; min-width: 0;">
                                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px; flex-wrap: wrap;">
                                    <span style="font-size: 11px; font-weight: 800; letter-spacing: 0.5px; background: {{ $statusStyles['badge_bg'] }}; color: white; padding: 4px 10px; border-radius: 5px;">
                                        {{ $statusStyles['label'] }}
                                    </span>
                                    @if($stupanjStyles)
                                        <span style="font-size: 11px; font-weight: 800; letter-spacing: 0.5px; background: {{ $stupanjStyles['bg'] }}; color: {{ $stupanjStyles['text'] }}; padding: 4px 10px; border-radius: 5px;">
                                            {{ $stupanjStyles['label'] }}
                                        </span>
                                    @endif
                                    @if($razinaLabel)
                                        <span style="font-size: 10px; font-weight: 700; letter-spacing: 0.5px; background: #E5E7EB; color: #374151; padding: 3px 8px; border-radius: 4px;">
                                            {{ $razinaLabel }}
                                        </span>
                                    @endif
                                </div>
                                
                                <div style="font-size: 18px; font-weight: 800; color: #111827; margin-bottom: 6px; line-height: 1.3;">
                                    {{ $dogadjaj->naziv }}
                                </div>
                                
                                <div style="font-size: 13px; color: #4B5563; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <span style="font-weight: 600;">{{ $dogadjaj->jls?->naziv ?? 'Cijela županija' }}</span>
                                    <span style="opacity: 0.5;">•</span>
                                    <span>{{ $tipLabel }}</span>
                                    @if($dogadjaj->voditelj)
                                        <span style="opacity: 0.5;">•</span>
                                        <span>Voditelj: <b>{{ $dogadjaj->voditelj->name }}</b></span>
                                    @endif
                                </div>
                                
                                <div style="display: flex; align-items: center; gap: 12px; margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(0,0,0,0.08);">
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#6B7280" width="14" height="14">
                                            <path d="M11 .982l-9 4.5v8.518c0 6.075 9 11 9 11s9-4.925 9-11V5.482l-9-4.5z"/>
                                        </svg>
                                        <span style="font-size: 12px; color: #4B5563;">
                                            <b>{{ $dogadjaj->dojave_count }}</b> {{ $dogadjaj->dojave_count == 1 ? 'dojava' : ($dogadjaj->dojave_count < 5 ? 'dojave' : 'dojava') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="text-align: right; flex-shrink: 0; min-width: 130px;">
                                <div style="font-size: 11px; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">
                                    Otvoreno prije
                                </div>
                                <div style="font-size: 20px; font-weight: 800; color: {{ $statusStyles['text'] }}; margin-top: 2px;">
                                    {{ $trajanje }}
                                </div>
                                <div style="font-size: 11px; color: #9CA3AF; margin-top: 4px; font-variant-numeric: tabular-nums;">
                                    {{ $dogadjaj->vrijeme_otvaranja->format('d.m. H:i') }}
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-widgets::widget>