<x-filament-panels::page>
    <div>
        <form wire:submit="save">
            {{ $this->form }}
            
            <div style="display: flex; gap: 12px; margin-top: 20px;">
                <x-filament::button type="submit">
                    Spremi izmjene
                </x-filament::button>
            </div>
        </form>

        <div style="margin-top: 32px; padding-top: 24px; border-top: 3px solid #DC2626;">
            
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                <div style="background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%); padding: 10px; border-radius: 10px; color: white;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                        <path fill-rule="evenodd" d="M12.963 2.286a.75.75 0 0 0-1.071-.136 9.742 9.742 0 0 0-3.539 6.176 7.547 7.547 0 0 1-1.705-1.715.75.75 0 0 0-1.152-.082A9 9 0 1 0 15.68 4.534a7.46 7.46 0 0 1-2.717-2.248Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <div style="font-size: 22px; font-weight: 900; color: #111827;">🎯 Vođenje intervencije</div>
                    <div style="font-size: 13px; color: #6B7280; margin-top: 2px;">
                        Formiraj timove, prati statuse, vidi timeline
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 380px; gap: 20px;">
                
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="font-size: 16px; font-weight: 800; color: #111827;">
                            Aktivni timovi 
                            <span style="background: #FEE2E2; color: #991B1B; padding: 2px 10px; border-radius: 12px; font-size: 12px; margin-left: 6px;">
                                {{ $timovi->count() }}
                            </span>
                        </div>
                        <div style="font-size: 12px; color: #6B7280;">
                            Koristi gumb "➕ Novi tim" na vrhu
                        </div>
                    </div>

                    @if($timovi->isEmpty())
                        <div style="background: #F9FAFB; border: 2px dashed #D1D5DB; border-radius: 12px; padding: 40px 20px; text-align: center;">
                            <div style="font-size: 48px; margin-bottom: 12px;">🚒</div>
                            <div style="font-size: 16px; font-weight: 700; color: #374151; margin-bottom: 6px;">
                                Još nema formiranih timova
                            </div>
                            <div style="font-size: 13px; color: #6B7280; max-width: 400px; margin: 0 auto;">
                                Klikni "➕ Novi tim" na vrhu da formiraš prvi tim.
                            </div>
                        </div>
                    @else
                        @foreach($timovi as $tim)
                            @php
                                $statusBoja = match($tim->trenutni_status) {
                                    'formiran' => ['bg' => '#FEF3C7', 'border' => '#F59E0B', 'text' => '#92400E', 'label' => 'FORMIRAN'],
                                    'polazak' => ['bg' => '#DBEAFE', 'border' => '#3B82F6', 'text' => '#1E40AF', 'label' => 'POLAZAK'],
                                    'na_mjestu' => ['bg' => '#FEE2E2', 'border' => '#DC2626', 'text' => '#991B1B', 'label' => 'NA MJESTU'],
                                    'intervencija_zavrsena' => ['bg' => '#D1FAE5', 'border' => '#10B981', 'text' => '#065F46', 'label' => 'ZAVRŠILI'],
                                    'povratak' => ['bg' => '#E0E7FF', 'border' => '#6366F1', 'text' => '#3730A3', 'label' => 'POVRATAK'],
                                    'odmor' => ['bg' => '#F3F4F6', 'border' => '#6B7280', 'text' => '#374151', 'label' => 'ODMOR'],
                                    'cekanje_u_bazi' => ['bg' => '#F3F4F6', 'border' => '#9CA3AF', 'text' => '#374151', 'label' => 'U BAZI'],
                                    default => ['bg' => '#F3F4F6', 'border' => '#6B7280', 'text' => '#374151', 'label' => strtoupper($tim->trenutni_status)],
                                };
                            @endphp
                            
                            <div style="background: white; border-radius: 12px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; border-left: 5px solid {{ $statusBoja['border'] }};">
                                
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; flex-wrap: wrap; gap: 8px;">
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                            <span style="font-size: 16px; font-weight: 800; color: #111827;">{{ $tim->naziv }}</span>
                                            <span style="font-size: 10px; font-weight: 800; background: {{ $statusBoja['border'] }}; color: white; padding: 3px 8px; border-radius: 4px;">
                                                {{ $statusBoja['label'] }}
                                            </span>
                                        </div>
                                        <div style="font-size: 12px; color: #6B7280; margin-top: 4px;">
                                            🏠 Baza: {{ $tim->bazaPostrojba?->naziv ?? '—' }}
                                            • 👤 Zapovjednik: {{ $tim->zapovjednik?->puno_ime ?? '—' }}
                                            • Formiran: {{ $tim->vrijeme_formiranja->format('H:i') }}
                                        </div>
                                    </div>
                                </div>

                                @if($tim->zadatak)
                                    <div style="background: #FFFBEB; border-left: 3px solid #F59E0B; padding: 8px 12px; border-radius: 4px; font-size: 12px; color: #92400E; margin-bottom: 12px;">
                                        <strong>Zadatak:</strong> {{ $tim->zadatak }}
                                    </div>
                                @endif

                                <div style="background: #F9FAFB; border-radius: 6px; padding: 10px; margin-bottom: 12px;">
                                    <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #6B7280; margin-bottom: 6px; letter-spacing: 0.5px;">
                                        🧑‍🚒 Članovi tima ({{ $tim->trenutniClanovi->count() }})
                                    </div>
                                    @if($tim->trenutniClanovi->isEmpty())
                                        <div style="font-size: 12px; color: #9CA3AF; font-style: italic;">Tim još nema članova</div>
                                    @else
                                        <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                            @foreach($tim->trenutniClanovi as $clan)
                                                @php
                                                    $ulogaIkona = $clan->uloga === 'zapovjednik' ? '👑' : '👤';
                                                @endphp
                                                <span style="display: inline-flex; align-items: center; gap: 4px; background: white; border: 1px solid #E5E7EB; padding: 4px 8px; border-radius: 4px; font-size: 11px; color: #374151;">
                                                    {{ $ulogaIkona }} {{ $clan->vatrogasac->puno_ime }}
                                                    <span style="color: #9CA3AF; font-size: 10px;">({{ $clan->vatrogasac->postrojba?->skraceni_naziv ?? '?' }})</span>
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                    <a href="/admin/timovi/{{ $tim->id }}" 
                                       style="display: inline-flex; align-items: center; gap: 4px; background: #3B82F6; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                        ✏ Upravljaj timom
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div style="background: white; border-radius: 12px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; height: fit-content; position: sticky; top: 20px;">
                    <div style="font-size: 14px; font-weight: 800; color: #111827; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px solid #F3F4F6;">
                        📋 Timeline intervencije
                    </div>

                    @if($timeline->isEmpty())
                        <div style="text-align: center; padding: 30px 10px; color: #9CA3AF;">
                            <div style="font-size: 32px; margin-bottom: 6px;">📜</div>
                            <div style="font-size: 12px;">Timeline će se popunjavati kako budu napredovali timovi</div>
                        </div>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 8px; max-height: 600px; overflow-y: auto;">
                            @foreach($timeline as $log)
                                @php
                                    $tipIkona = match($log->status) {
                                        'formiran' => '🆕',
                                        'polazak' => '🚒',
                                        'na_mjestu' => '📍',
                                        'intervencija_zavrsena' => '✅',
                                        'povratak' => '↩️',
                                        'odmor' => '😴',
                                        'cekanje_u_bazi' => '🏠',
                                        'raspusten' => '🚪',
                                        default => '📌',
                                    };
                                @endphp
                                <div style="border-left: 3px solid #DC2626; padding: 8px 10px; background: #FEF2F2; border-radius: 4px;">
                                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
                                        <span style="font-size: 14px;">{{ $tipIkona }}</span>
                                        <span style="font-size: 11px; font-weight: 800; color: #991B1B;">
                                            {{ strtoupper(str_replace('_', ' ', $log->status)) }}
                                        </span>
                                        <span style="font-size: 10px; color: #6B7280; margin-left: auto;">
                                            {{ $log->vrijeme->format('H:i:s') }}
                                        </span>
                                    </div>
                                    <div style="font-size: 12px; color: #111827; font-weight: 600;">
                                        {{ $log->tim?->naziv ?? 'Tim' }}
                                    </div>
                                    @if($log->napomena)
                                        <div style="font-size: 11px; color: #6B7280; margin-top: 2px;">{{ $log->napomena }}</div>
                                    @endif
                                    @if($log->autor)
                                        <div style="font-size: 10px; color: #9CA3AF; margin-top: 4px;">
                                            — {{ $log->autor->name }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-filament-panels::page>
