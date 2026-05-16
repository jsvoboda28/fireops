<x-filament-panels::page>
    <div>
        {{-- Form za edit --}}
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
                        <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0Z"/>
                    </svg>
                </div>
                <div>
                    <div style="font-size: 22px; font-weight: 900; color: #111827;">👥 Sastav tima {{ $tim->naziv }}</div>
                    <div style="font-size: 13px; color: #6B7280; margin-top: 2px;">
                        {{ $trenutniClanovi->count() }} članova · {{ $trenutnaVozila->count() }} vozila
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 380px; gap: 20px;">
                
                {{-- ===== LIJEVO: ČLANOVI + VOZILA ===== --}}
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    
                    {{-- ČLANOVI --}}
                    <div>
                        <div style="font-size: 14px; font-weight: 800; color: #111827; padding-bottom: 8px; border-bottom: 2px solid #F3F4F6; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                            🧑‍🚒 Trenutni članovi tima
                            <span style="background: #DBEAFE; color: #1E40AF; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: 700;">
                                {{ $trenutniClanovi->count() }}
                            </span>
                        </div>

                        @if($trenutniClanovi->isEmpty())
                            <div style="background: #F9FAFB; border: 2px dashed #D1D5DB; border-radius: 12px; padding: 20px; text-align: center;">
                                <div style="font-size: 28px; margin-bottom: 4px;">👥</div>
                                <div style="font-size: 12px; color: #6B7280;">Nema članova — klikni "➕ Dodaj vatrogasca"</div>
                            </div>
                        @else
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                @foreach($trenutniClanovi as $clan)
                                    @php
                                        $jeZapovjednik = $clan->uloga === 'zapovjednik';
                                        $ulogaIkona = $jeZapovjednik ? '👑' : '👤';
                                        $ulogaBoja = $jeZapovjednik ? '#F59E0B' : '#3B82F6';
                                    @endphp
                                    <div style="background: white; border-radius: 8px; padding: 10px 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; border-left: 4px solid {{ $ulogaBoja }}; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                        <div style="flex: 1; min-width: 0;">
                                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                                <span style="font-size: 16px;">{{ $ulogaIkona }}</span>
                                                <span style="font-size: 14px; font-weight: 700; color: #111827;">
                                                    {{ $clan->vatrogasac->puno_ime }}
                                                </span>
                                                @if($jeZapovjednik)
                                                    <span style="font-size: 9px; font-weight: 800; background: #F59E0B; color: white; padding: 2px 6px; border-radius: 3px;">
                                                        ZAPOVJEDNIK
                                                    </span>
                                                @endif
                                            </div>
                                            <div style="font-size: 11px; color: #6B7280; margin-top: 2px;">
                                                🏢 {{ $clan->vatrogasac->postrojba?->naziv ?? '—' }}
                                                • U timu od {{ $clan->usao_u->format('H:i') }}
                                                ({{ $clan->usao_u->diffForHumans(null, true, true) }})
                                            </div>
                                        </div>
                                        <button wire:click="skiniClana({{ $clan->id }})"
                                                wire:confirm="Skinuti {{ $clan->vatrogasac->puno_ime }} iz tima?"
                                                style="background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                            Skini
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- VOZILA --}}
                    <div>
                        <div style="font-size: 14px; font-weight: 800; color: #111827; padding-bottom: 8px; border-bottom: 2px solid #F3F4F6; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                            🚒 Vozila u timu
                            <span style="background: #FEF3C7; color: #92400E; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: 700;">
                                {{ $trenutnaVozila->count() }}
                            </span>
                        </div>

                        @if($trenutnaVozila->isEmpty())
                            <div style="background: #F9FAFB; border: 2px dashed #D1D5DB; border-radius: 12px; padding: 20px; text-align: center;">
                                <div style="font-size: 28px; margin-bottom: 4px;">🚒</div>
                                <div style="font-size: 12px; color: #6B7280;">Nema vozila — klikni "🚒 Dodaj vozilo"</div>
                            </div>
                        @else
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                @foreach($trenutnaVozila as $tv)
                                    <div style="background: white; border-radius: 8px; padding: 10px 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; border-left: 4px solid #F59E0B; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
                                        <div style="flex: 1; min-width: 0;">
                                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                                <span style="font-size: 16px;">🚒</span>
                                                <span style="font-size: 14px; font-weight: 800; color: #111827;">
                                                    {{ $tv->vozilo->registracija ?? '?' }}
                                                </span>
                                                <span style="font-size: 12px; color: #6B7280;">
                                                    {{ $tv->vozilo->marka ?? '' }} {{ $tv->vozilo->model ?? '' }}
                                                </span>
                                            </div>
                                            <div style="font-size: 11px; color: #6B7280; margin-top: 2px;">
                                                🏢 {{ $tv->vozilo->postrojba?->naziv ?? '—' }}
                                                • U timu od {{ $tv->dodano_u->format('H:i') }}
                                                ({{ $tv->dodano_u->diffForHumans(null, true, true) }})
                                            </div>
                                        </div>
                                        <button wire:click="skiniVozilo({{ $tv->id }})"
                                                wire:confirm="Skinuti vozilo {{ $tv->vozilo->registracija ?? '?' }} iz tima?"
                                                style="background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer;">
                                            Skini
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Povijest članstva --}}
                    @if($povijestClanstva->isNotEmpty())
                        <div>
                            <div style="font-size: 14px; font-weight: 800; color: #6B7280; padding-bottom: 8px; border-bottom: 2px solid #F3F4F6; margin-bottom: 8px;">
                                📜 Povijest članstva
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                @foreach($povijestClanstva as $stari)
                                    @php
                                        $minuta = (int) $stari->usao_u->diffInMinutes($stari->izasao_u);
                                        $trajanje = $minuta < 60 ? $minuta . ' min' : floor($minuta / 60) . 'h ' . ($minuta % 60) . 'min';
                                    @endphp
                                    <div style="background: #F9FAFB; border-radius: 6px; padding: 8px 12px; font-size: 12px; color: #4B5563; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                        <span style="color: #9CA3AF;">{{ $stari->uloga === 'zapovjednik' ? '👑' : '👤' }}</span>
                                        <span style="font-weight: 600; color: #374151;">{{ $stari->vatrogasac->puno_ime }}</span>
                                        <span style="color: #9CA3AF;">•</span>
                                        <span>{{ $stari->vatrogasac->postrojba?->skraceni_naziv ?? '?' }}</span>
                                        <span style="color: #9CA3AF;">•</span>
                                        <span>{{ $stari->usao_u->format('d.m. H:i') }} → {{ $stari->izasao_u->format('H:i') }}</span>
                                        <span style="margin-left: auto; background: white; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: 600;">
                                            {{ $trajanje }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Povijest vozila --}}
                    @if($povijestVozila->isNotEmpty())
                        <div>
                            <div style="font-size: 14px; font-weight: 800; color: #6B7280; padding-bottom: 8px; border-bottom: 2px solid #F3F4F6; margin-bottom: 8px;">
                                📜 Povijest vozila
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 6px;">
                                @foreach($povijestVozila as $stari)
                                    @php
                                        $minuta = (int) $stari->dodano_u->diffInMinutes($stari->skinuto_u);
                                        $trajanje = $minuta < 60 ? $minuta . ' min' : floor($minuta / 60) . 'h ' . ($minuta % 60) . 'min';
                                    @endphp
                                    <div style="background: #F9FAFB; border-radius: 6px; padding: 8px 12px; font-size: 12px; color: #4B5563; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                        <span style="color: #9CA3AF;">🚒</span>
                                        <span style="font-weight: 600; color: #374151;">{{ $stari->vozilo->registracija ?? '?' }}</span>
                                        <span style="color: #9CA3AF;">•</span>
                                        <span>{{ $stari->vozilo->postrojba?->skraceni_naziv ?? '?' }}</span>
                                        <span style="color: #9CA3AF;">•</span>
                                        <span>{{ $stari->dodano_u->format('d.m. H:i') }} → {{ $stari->skinuto_u->format('H:i') }}</span>
                                        <span style="margin-left: auto; background: white; padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: 600;">
                                            {{ $trajanje }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ===== DESNO: STATUS LOG ===== --}}
                <div style="background: white; border-radius: 12px; padding: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; height: fit-content; position: sticky; top: 20px;">
                    <div style="font-size: 14px; font-weight: 800; color: #111827; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px solid #F3F4F6;">
                        📋 Status povijest tima
                    </div>

                    @if($statusLog->isEmpty())
                        <div style="text-align: center; padding: 30px 10px; color: #9CA3AF;">
                            <div style="font-size: 32px; margin-bottom: 6px;">📜</div>
                            <div style="font-size: 12px;">Povijest će se popunjavati</div>
                        </div>
                    @else
                        <div style="display: flex; flex-direction: column; gap: 8px; max-height: 700px; overflow-y: auto;">
                            @foreach($statusLog as $log)
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
                                            {{ $log->vrijeme->format('d.m. H:i') }}
                                        </span>
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
