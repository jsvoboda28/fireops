@php
    $statusBoja = match($tim->trenutni_status) {
        'formiran' => ['bg' => '#FEF3C7', 'border' => '#F59E0B', 'text' => '#92400E', 'label' => '🆕 FORMIRAN'],
        'polazak' => ['bg' => '#DBEAFE', 'border' => '#3B82F6', 'text' => '#1E40AF', 'label' => '🚒 POLAZAK'],
        'na_mjestu' => ['bg' => '#FEE2E2', 'border' => '#DC2626', 'text' => '#991B1B', 'label' => '📍 NA MJESTU'],
        'intervencija_zavrsena' => ['bg' => '#D1FAE5', 'border' => '#10B981', 'text' => '#065F46', 'label' => '✅ ZAVRŠILI'],
        'povratak' => ['bg' => '#E0E7FF', 'border' => '#6366F1', 'text' => '#3730A3', 'label' => '↩️ POVRATAK'],
        'odmor' => ['bg' => '#F3F4F6', 'border' => '#6B7280', 'text' => '#374151', 'label' => '😴 ODMOR'],
        'cekanje_u_bazi' => ['bg' => '#F3F4F6', 'border' => '#9CA3AF', 'text' => '#374151', 'label' => '🏠 U BAZI'],
        'raspusten' => ['bg' => '#F3F4F6', 'border' => '#9CA3AF', 'text' => '#6B7280', 'label' => '🚪 RASPUŠTEN'],
        default => ['bg' => '#F3F4F6', 'border' => '#6B7280', 'text' => '#374151', 'label' => strtoupper($tim->trenutni_status)],
    };
    $jeRaspusten = $tim->trenutni_status === 'raspusten';
@endphp

<div class="fo-card" style="background: white; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; border-left: 5px solid {{ $statusBoja['border'] }}; overflow: hidden; {{ $jeRaspusten ? 'opacity: 0.6;' : '' }}">
    
    {{-- KOMPAKTAN HEADER (uvijek vidljiv) --}}
    <div style="padding: 10px 14px; background: {{ $statusBoja['bg'] }};">
        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <span style="font-size: 16px; font-weight: 800; color: #111827;">{{ $tim->naziv }}</span>
            <span style="font-size: 10px; font-weight: 800; background: {{ $statusBoja['border'] }}; color: white; padding: 3px 8px; border-radius: 4px;">
                {{ $statusBoja['label'] }}
            </span>
            <div style="margin-left: auto; display: flex; align-items: center; gap: 8px; font-size: 11px; color: {{ $statusBoja['text'] }};">
                <span title="Članovi">🧑‍🚒 {{ $tim->trenutniClanovi->count() }}</span>
                <span title="Vozila">🚒 {{ $tim->trenutnaVozila->count() }}</span>
                <a href="/admin/tims/{{ $tim->id }}/edit" 
                   style="background: white; color: #374151; border: 1px solid #D1D5DB; padding: 3px 10px; border-radius: 5px; font-size: 10px; font-weight: 700; text-decoration: none;">
                    ⚙ Upravljaj
                </a>
            </div>
        </div>
        <div style="font-size: 11px; color: {{ $statusBoja['text'] }}; margin-top: 4px;">
            🏠 <strong>{{ $tim->bazaPostrojba?->skraceni_naziv ?? $tim->bazaPostrojba?->naziv ?? '—' }}</strong>
            • 👑 {{ $tim->zapovjednik?->puno_ime ?? '—' }}
            • Formiran u {{ $tim->vrijeme_formiranja->format('H:i') }}
            ({{ $tim->vrijeme_formiranja->diffForHumans(null, true, true) }})
        </div>
    </div>

    {{-- 1-CLICK AKCIJE --}}
    @if(!$jeRaspusten)
        <div style="padding: 8px 14px; background: white; border-top: 1px solid #F3F4F6; display: flex; gap: 5px; flex-wrap: wrap; align-items: center;">
            
            @if(in_array($tim->trenutni_status, ['formiran', 'cekanje_u_bazi', 'odmor']))
                <button wire:click="timPolazak({{ $tim->id }})" class="fo-btn"
                        style="background: #3B82F6; color: white; border: none; padding: 5px 12px; border-radius: 5px; font-size: 11px; font-weight: 700;">
                    🚒 Polazak
                </button>
            @endif

            @if($tim->trenutni_status === 'polazak')
                <button wire:click="timNaMjestu({{ $tim->id }})" class="fo-btn"
                        style="background: #DC2626; color: white; border: none; padding: 5px 12px; border-radius: 5px; font-size: 11px; font-weight: 700;">
                    📍 Na mjestu
                </button>
            @endif

            @if($tim->trenutni_status === 'na_mjestu')
                <button wire:click="timZavrsili({{ $tim->id }})" class="fo-btn"
                        style="background: #10B981; color: white; border: none; padding: 5px 12px; border-radius: 5px; font-size: 11px; font-weight: 700;">
                    ✅ Završili
                </button>
            @endif

            @if(in_array($tim->trenutni_status, ['intervencija_zavrsena', 'na_mjestu']))
                <button wire:click="timPovratak({{ $tim->id }})" class="fo-btn"
                        style="background: #6366F1; color: white; border: none; padding: 5px 12px; border-radius: 5px; font-size: 11px; font-weight: 700;">
                    ↩️ Povratak
                </button>
            @endif

            <a href="/admin/tims/{{ $tim->id }}/edit"
               style="background: #F3F4F6; color: #374151; border: 1px solid #D1D5DB; padding: 5px 12px; border-radius: 5px; font-size: 11px; font-weight: 600; text-decoration: none; margin-left: auto;">
                ➕ Članovi / vozila
            </a>
        </div>
    @endif

    {{-- COLLAPSED DETALJI (klikni "Pokaži") --}}
    @if($tim->zadatak || $tim->trenutniClanovi->isNotEmpty() || $tim->trenutnaVozila->isNotEmpty())
        <details>
            <summary style="cursor: pointer; padding: 6px 14px; background: #F9FAFB; font-size: 10px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.5px; border-top: 1px solid #F3F4F6;">
                ▼ Detalji
            </summary>
            <div style="padding: 12px 14px; background: #F9FAFB;">
                @if($tim->zadatak)
                    <div style="background: #FFFBEB; border-left: 3px solid #F59E0B; padding: 6px 10px; border-radius: 4px; font-size: 12px; color: #92400E; margin-bottom: 10px;">
                        <strong>Zadatak:</strong> {{ $tim->zadatak }}
                    </div>
                @endif

                @if($tim->trenutniClanovi->isNotEmpty())
                    <div style="margin-bottom: 8px;">
                        <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #6B7280; margin-bottom: 4px;">
                            🧑‍🚒 Članovi
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                            @foreach($tim->trenutniClanovi as $clan)
                                <span style="background: white; border: 1px solid #E5E7EB; padding: 3px 8px; border-radius: 4px; font-size: 11px; color: #374151;">
                                    {{ $clan->uloga === 'zapovjednik' ? '👑' : '👤' }}
                                    {{ $clan->vatrogasac->prezime }} {{ $clan->vatrogasac->ime }}
                                    <span style="color: #9CA3AF; font-size: 10px;">({{ $clan->vatrogasac->postrojba?->skraceni_naziv ?? '?' }})</span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($tim->trenutnaVozila->isNotEmpty())
                    <div>
                        <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; color: #6B7280; margin-bottom: 4px;">
                            🚒 Vozila
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                            @foreach($tim->trenutnaVozila as $tv)
                                <span style="background: white; border: 1px solid #E5E7EB; padding: 3px 8px; border-radius: 4px; font-size: 11px; color: #374151;">
                                    🚒 <strong>{{ $tv->vozilo->registracija ?? '?' }}</strong>
                                    <span style="color: #9CA3AF; font-size: 10px;">({{ $tv->vozilo->postrojba?->skraceni_naziv ?? '?' }})</span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </details>
    @endif
</div>
