@php
    $tim = $upravljaniTim;
@endphp

@if($tim)
<div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); z-index: 5000; display: flex; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(4px);"
     wire:click.self="zatvoriModal">
    <div style="background: white; border-radius: 14px; box-shadow: 0 24px 48px rgba(0,0,0,0.25); width: 100%; max-width: 1100px; max-height: 92vh; overflow: hidden; display: flex; flex-direction: column;">

        @php
            $statusBoja = match($tim->trenutni_status) {
                'na_mjestu' => 'linear-gradient(135deg, #DC2626 0%, #991B1B 100%)',
                'polazak' => 'linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%)',
                'povratak' => 'linear-gradient(135deg, #6366F1 0%, #3730A3 100%)',
                'formiran' => 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)',
                'intervencija_zavrsena' => 'linear-gradient(135deg, #10B981 0%, #047857 100%)',
                default => 'linear-gradient(135deg, #64748B 0%, #334155 100%)',
            };
        @endphp

        <div style="padding: 14px 20px; background: {{ $statusBoja }}; color: white; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;">
            <div>
                <div style="font-size: 9px; font-weight: 700; opacity: 0.9; text-transform: uppercase; letter-spacing: 1px;">UPRAVLJANJE TIMOM</div>
                <div style="font-size: 18px; font-weight: 900; margin-top: 2px;">⚙ {{ $tim->naziv }}</div>
                <div style="font-size: 11px; opacity: 0.9; margin-top: 2px;">
                    🏠 {{ $tim->bazaPostrojba?->skraceni_naziv ?? $tim->bazaPostrojba?->naziv ?? '—' }}
                    • 👑 {{ $tim->zapovjednik?->puno_ime ?? '—' }}
                    • {{ strtoupper(str_replace('_', ' ', $tim->trenutni_status)) }}
                </div>
            </div>
            <button wire:click="zatvoriModal" style="background: rgba(0,0,0,0.25); color: white; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 14px; font-weight: 800;">✕</button>
        </div>

        <div style="padding: 16px 20px; overflow-y: auto; flex: 1; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px;">

            {{-- KOLONA 1: ČLANOVI --}}
            <div>
                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 10px;">
                    <span style="font-size: 16px;">🧑‍🚒</span>
                    <span style="font-size: 13px; font-weight: 800; color: #0F172A;">Članovi</span>
                    <span style="background: #F1F5F9; color: #475569; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 800;">{{ $tim->trenutniClanovi->count() }}</span>
                </div>

                <div style="max-height: 280px; overflow-y: auto; margin-bottom: 10px;">
                    @forelse($tim->trenutniClanovi as $clan)
                        @php
                            $ulogaBoja = match($clan->uloga) {
                                'zapovjednik' => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'label' => '👑'],
                                'vozac' => ['bg' => '#DBEAFE', 'text' => '#1E40AF', 'label' => '🚗'],
                                default => ['bg' => '#F1F5F9', 'text' => '#475569', 'label' => '🧑‍🚒'],
                            };
                        @endphp
                        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 7px 9px; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                            <span style="font-size: 14px;">{{ $ulogaBoja['label'] }}</span>
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-size: 11px; font-weight: 800; color: #0F172A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $clan->vatrogasac?->prezime ?? '?' }} {{ $clan->vatrogasac?->ime ?? '' }}
                                </div>
                                <div style="font-size: 9px; color: #64748B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $clan->vatrogasac?->postrojba?->skraceni_naziv ?? $clan->vatrogasac?->postrojba?->naziv ?? '—' }}
                                </div>
                            </div>
                            @if($clan->uloga !== 'zapovjednik')
                                <button wire:click="ukloniClanaIzTima({{ $clan->id }})"
                                        wire:confirm="Ukloniti člana iz tima?"
                                        style="background: #FEE2E2; color: #991B1B; border: none; width: 20px; height: 20px; border-radius: 4px; cursor: pointer; font-size: 11px; font-weight: 800; flex-shrink: 0;"
                                        title="Ukloni">
                                    ✕
                                </button>
                            @endif
                        </div>
                    @empty
                        <div style="text-align: center; padding: 16px; color: #94A3B8; font-size: 11px;">
                            Nema članova
                        </div>
                    @endforelse
                </div>

                <div style="background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 8px; padding: 10px;">
                    <div style="font-size: 10px; font-weight: 800; color: #065F46; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">➕ Dodaj člana</div>

                    <select wire:model="upravljanjeNoviClanId" class="fo-input" style="font-size: 11px; margin-bottom: 5px;">
                        <option value="">-- Odaberi vatrogasca --</option>
                        @foreach($this->vatrogasciOpcije as $id => $naziv)
                            <option value="{{ $id }}">{{ $naziv }}</option>
                        @endforeach
                    </select>

                    <select wire:model="upravljanjeNovaUloga" class="fo-input" style="font-size: 11px; margin-bottom: 7px;">
                        <option value="clan">🧑‍🚒 Član</option>
                        <option value="vozac">🚗 Vozač</option>
                    </select>

                    <button wire:click="dodajClanaUTim"
                            style="width: 100%; background: linear-gradient(135deg, #10B981 0%, #047857 100%); color: white; border: none; padding: 7px; border-radius: 6px; font-size: 11px; font-weight: 800; cursor: pointer;">
                        ➕ Dodaj
                    </button>
                </div>
            </div>

            {{-- KOLONA 2: VOZILA --}}
            <div>
                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 10px;">
                    <span style="font-size: 16px;">🚒</span>
                    <span style="font-size: 13px; font-weight: 800; color: #0F172A;">Vozila</span>
                    <span style="background: #F1F5F9; color: #475569; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 800;">{{ $tim->trenutnaVozila?->count() ?? 0 }}</span>
                </div>

                <div style="max-height: 280px; overflow-y: auto; margin-bottom: 10px;">
                    @forelse($tim->trenutnaVozila ?? [] as $tv)
                        @php
                            $vozilo = $tv->vozilo;
                            if (!$vozilo) continue;
                            
                            $tipIkona = match(strtolower($vozilo->tip ?? '')) {
                                'navalno' => '🚒',
                                'cisterna' => '🚛',
                                'auto-platforma' => '🪜',
                                'kombi' => '🚐',
                                'osobno' => '🚗',
                                'kamion' => '🚚',
                                'specijalno' => '🛠',
                                default => '🚙',
                            };
                            
                            $isteklaRegistracija = $vozilo->registrirano_do && $vozilo->registrirano_do->isPast();
                            $istekaoPregled = $vozilo->tehnicki_pregled_do && $vozilo->tehnicki_pregled_do->isPast();
                        @endphp
                        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 7px 9px; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
                            <span style="font-size: 16px;">{{ $tipIkona }}</span>
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-size: 11px; font-weight: 800; color: #0F172A; display: flex; align-items: center; gap: 4px;">
                                    <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $vozilo->registracija }}</span>
                                    @if($isteklaRegistracija)
                                        <span style="font-size: 8px; background: #FEE2E2; color: #991B1B; padding: 1px 4px; border-radius: 3px; font-weight: 800;" title="Registracija istekla">REG✕</span>
                                    @endif
                                    @if($istekaoPregled)
                                        <span style="font-size: 8px; background: #FEE2E2; color: #991B1B; padding: 1px 4px; border-radius: 3px; font-weight: 800;" title="Tehnički pregled istekao">TP✕</span>
                                    @endif
                                </div>
                                <div style="font-size: 9px; color: #64748B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ strtoupper($vozilo->tip ?? '?') }} • {{ $vozilo->marka }}@if($vozilo->model) {{ $vozilo->model }}@endif
                                </div>
                                <div style="font-size: 9px; color: #94A3B8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    🏠 {{ $vozilo->postrojba?->skraceni_naziv ?? $vozilo->postrojba?->naziv ?? '—' }}
                                </div>
                            </div>
                            <button wire:click="ukloniVoziloIzTima({{ $tv->id }})"
                                    wire:confirm="Ukloniti vozilo iz tima?"
                                    style="background: #FEE2E2; color: #991B1B; border: none; width: 20px; height: 20px; border-radius: 4px; cursor: pointer; font-size: 11px; font-weight: 800; flex-shrink: 0;"
                                    title="Ukloni">
                                ✕
                            </button>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 16px; color: #94A3B8; font-size: 11px;">
                            Bez vozila
                        </div>
                    @endforelse
                </div>

                <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 10px;">
                    <div style="font-size: 10px; font-weight: 800; color: #1E40AF; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">➕ Dodaj vozilo</div>

                    <select wire:model="upravljanjeNovoVoziloId" class="fo-input" style="font-size: 10px; margin-bottom: 7px;">
                        <option value="">-- Odaberi vozilo --</option>
                        @foreach($this->vozilaOpcije as $id => $naziv)
                            <option value="{{ $id }}">{{ $naziv }}</option>
                        @endforeach
                    </select>

                    <button wire:click="dodajVoziloUTim"
                            style="width: 100%; background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%); color: white; border: none; padding: 7px; border-radius: 6px; font-size: 11px; font-weight: 800; cursor: pointer;">
                        ➕ Dodaj
                    </button>
                    
                    <div style="font-size: 9px; color: #64748B; margin-top: 6px; line-height: 1.4;">
                        ⭐ označava vozila iz baze tima<br>
                        Prikazana su samo operativna vozila
                    </div>
                </div>
            </div>

            {{-- KOLONA 3: DETALJI + ZADATAK + STATUS --}}
            <div>
                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 10px;">
                    <span style="font-size: 16px;">📋</span>
                    <span style="font-size: 13px; font-weight: 800; color: #0F172A;">Detalji & akcije</span>
                </div>

                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 10px; margin-bottom: 10px; font-size: 11px; line-height: 1.6;">
                    <div style="margin-bottom: 4px;"><strong style="color: #64748B;">Formiran:</strong> {{ $tim->vrijeme_formiranja?->format('d.m.Y H:i') ?? '—' }}</div>
                    <div style="margin-bottom: 4px;"><strong style="color: #64748B;">Baza:</strong> {{ $tim->bazaPostrojba?->naziv ?? '—' }}</div>
                    <div><strong style="color: #64748B;">Zapovjednik:</strong> {{ $tim->zapovjednik?->puno_ime ?? '—' }}</div>
                </div>

                <div style="background: #FFFBEB; border: 1px solid #FACC15; border-radius: 8px; padding: 10px; margin-bottom: 10px;">
                    <div style="font-size: 10px; font-weight: 800; color: #854D0E; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">📝 Zadatak</div>

                    <textarea wire:model="upravljanjeNoviZadatak"
                              rows="4"
                              placeholder="Što tim treba učiniti?"
                              class="fo-input"
                              style="font-size: 11px; resize: vertical; margin-bottom: 7px;"></textarea>

                    <button wire:click="azurirajZadatakTima"
                            style="width: 100%; background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); color: white; border: none; padding: 7px; border-radius: 6px; font-size: 11px; font-weight: 800; cursor: pointer;">
                        💾 Spremi zadatak
                    </button>
                </div>

                <div style="background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 10px;">
                    <div style="font-size: 10px; font-weight: 800; color: #1E40AF; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">⚡ Brza promjena statusa</div>
                    <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                        @if(in_array($tim->trenutni_status, ['formiran', 'cekanje_u_bazi', 'odmor']))
                            <button wire:click="timPolazak({{ $tim->id }})" style="background: #3B82F6; color: white; border: none; padding: 5px 9px; border-radius: 4px; font-size: 10px; font-weight: 700; cursor: pointer;">🚒 Polazak</button>
                        @endif
                        @if($tim->trenutni_status === 'polazak')
                            <button wire:click="timNaMjestu({{ $tim->id }})" style="background: #DC2626; color: white; border: none; padding: 5px 9px; border-radius: 4px; font-size: 10px; font-weight: 700; cursor: pointer;">📍 Na mjestu</button>
                        @endif
                        @if($tim->trenutni_status === 'na_mjestu')
                            <button wire:click="timZavrsili({{ $tim->id }})" style="background: #10B981; color: white; border: none; padding: 5px 9px; border-radius: 4px; font-size: 10px; font-weight: 700; cursor: pointer;">✅ Završili</button>
                        @endif
                        @if(in_array($tim->trenutni_status, ['intervencija_zavrsena', 'na_mjestu']))
                            <button wire:click="timPovratak({{ $tim->id }})" style="background: #6366F1; color: white; border: none; padding: 5px 9px; border-radius: 4px; font-size: 10px; font-weight: 700; cursor: pointer;">↩️ Povratak</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div style="padding: 12px 20px; background: #F8FAFC; border-top: 1px solid #E2E8F0; display: flex; justify-content: flex-end; flex-shrink: 0;">
            <button wire:click="zatvoriModal" style="background: white; border: 1px solid #E2E8F0; color: #475569; padding: 9px 20px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer;">
                Zatvori
            </button>
        </div>
    </div>
</div>
@endif
