@php
    $prio = match($intervencija->prioritet) {
        'kriticna' => ['bg' => 'linear-gradient(135deg, #EF4444 0%, #991B1B 100%)', 'border' => '#DC2626', 'badge' => 'KRITIČNA'],
        'visoka' => ['bg' => 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)', 'border' => '#F59E0B', 'badge' => 'VISOKA'],
        default => ['bg' => 'linear-gradient(135deg, #10B981 0%, #047857 100%)', 'border' => '#10B981', 'badge' => 'STANDARDNA'],
    };
    $aktivniTimovi = $intervencija->timovi->where('trenutni_status', '!=', 'raspusten');
    $brojLjudi = $aktivniTimovi->sum(fn($t) => $t->trenutniClanovi->count());
    $timoviNaMjestu = $aktivniTimovi->where('trenutni_status', 'na_mjestu');
    $timoviPolazak = $aktivniTimovi->where('trenutni_status', 'polazak');
    $timoviPovratak = $aktivniTimovi->where('trenutni_status', 'povratak');
@endphp

{{-- HEADER --}}
<div style="background: {{ $prio['bg'] }}; padding: 16px 20px; color: white; flex-shrink: 0; position: relative;">
    <button wire:click="zatvoriDetalje"
            style="position: absolute; top: 12px; right: 12px; background: rgba(0,0,0,0.25); color: white; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 16px; font-weight: 800;">
        ✕
    </button>
    
    <div style="font-size: 9px; font-weight: 700; opacity: 0.9; text-transform: uppercase; letter-spacing: 1.5px;">🔥 INTERVENCIJA • {{ $prio['badge'] }}</div>
    <div style="font-size: 18px; font-weight: 900; letter-spacing: -0.3px; line-height: 1.2; margin-top: 3px;">{{ $intervencija->naziv }}</div>
    <div style="font-size: 12px; opacity: 0.9; margin-top: 4px;">#{{ $intervencija->broj }} • {{ $intervencija->jls?->naziv ?? '—' }}</div>
</div>

{{-- BODY --}}
<div class="fo-scroll" style="overflow-y: auto; flex: 1; padding: 16px 20px;">
    
    {{-- BROJAČI --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; margin-bottom: 18px;">
        <div style="background: #F8FAFC; border-radius: 10px; padding: 12px; text-align: center; border: 1px solid #E2E8F0;">
            <div style="font-size: 24px; font-weight: 900; color: {{ $prio['border'] }}; line-height: 1;">{{ $aktivniTimovi->count() }}</div>
            <div style="font-size: 9px; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; margin-top: 4px;">Timova</div>
        </div>
        <div style="background: #F8FAFC; border-radius: 10px; padding: 12px; text-align: center; border: 1px solid #E2E8F0;">
            <div style="font-size: 24px; font-weight: 900; color: {{ $prio['border'] }}; line-height: 1;">{{ $brojLjudi }}</div>
            <div style="font-size: 9px; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; margin-top: 4px;">Ljudi</div>
        </div>
        <div style="background: #F8FAFC; border-radius: 10px; padding: 12px; text-align: center; border: 1px solid #E2E8F0;">
            <div style="font-size: 24px; font-weight: 900; color: {{ $prio['border'] }}; line-height: 1;">{{ $intervencija->dojave->count() }}</div>
            <div style="font-size: 9px; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; margin-top: 4px;">Dojava</div>
        </div>
    </div>
    
    {{-- LOKACIJA --}}
    @if($intervencija->adresa)
        <div style="margin-bottom: 18px;">
            <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #94A3B8; margin-bottom: 6px;">📍 LOKACIJA</div>
            <div style="font-size: 14px; font-weight: 700; color: #0F172A;">{{ $intervencija->adresa }}</div>
        </div>
    @endif
    
    {{-- VRIJEME --}}
    <div style="margin-bottom: 18px;">
        <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #94A3B8; margin-bottom: 6px;">🕐 OTVORENA</div>
        <div style="font-size: 14px; font-weight: 700; color: #0F172A;">{{ $intervencija->vrijeme_otvaranja->format('d.m.Y H:i') }}</div>
        <div style="font-size: 11px; color: #64748B; margin-top: 2px;">traje {{ $intervencija->vrijeme_otvaranja->diffForHumans(null, true, true) }}</div>
    </div>
    
    {{-- TIMOVI PREGLED --}}
    @if($aktivniTimovi->isNotEmpty())
        <div style="margin-bottom: 18px;">
            <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #94A3B8; margin-bottom: 8px;">🚒 ANGAŽIRANI TIMOVI</div>
            <div style="display: flex; flex-direction: column; gap: 6px;">
                @foreach($aktivniTimovi as $tim)
                    @php
                        $statusBoja = match($tim->trenutni_status) {
                            'na_mjestu' => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'label' => '📍 NA MJESTU'],
                            'polazak' => ['bg' => '#DBEAFE', 'text' => '#1E40AF', 'label' => '🚒 POLAZAK'],
                            'povratak' => ['bg' => '#E0E7FF', 'text' => '#3730A3', 'label' => '↩️ POVRATAK'],
                            'formiran' => ['bg' => '#FEF3C7', 'text' => '#92400E', 'label' => '🆕 FORMIRAN'],
                            default => ['bg' => '#F1F5F9', 'text' => '#475569', 'label' => strtoupper(str_replace('_', ' ', $tim->trenutni_status))],
                        };
                    @endphp
                    <div style="background: #F8FAFC; border-radius: 8px; padding: 8px 12px; border: 1px solid #E2E8F0; display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 9px; font-weight: 800; background: {{ $statusBoja['bg'] }}; color: {{ $statusBoja['text'] }}; padding: 3px 7px; border-radius: 4px;">{{ $statusBoja['label'] }}</span>
                        <div style="flex: 1;">
                            <div style="font-size: 12px; font-weight: 800; color: #0F172A;">{{ $tim->naziv }}</div>
                            <div style="font-size: 10px; color: #64748B;">{{ $tim->zapovjednik?->puno_ime ?? '—' }} • 🧑‍🚒 {{ $tim->trenutniClanovi->count() }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div style="background: #FEF9C3; border: 1px solid #FACC15; border-radius: 10px; padding: 14px; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 18px;">⚠</span>
            <div>
                <div style="font-size: 13px; font-weight: 800; color: #854D0E;">Bez angažiranih timova</div>
                <div style="font-size: 11px; color: #92400E;">Otvori "Vodi intervenciju" za dodavanje</div>
            </div>
        </div>
    @endif
    
    {{-- DOJAVE VEZANE --}}
    @if($intervencija->dojave->isNotEmpty())
        <div style="margin-bottom: 18px;">
            <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #94A3B8; margin-bottom: 8px;">📞 VEZANE DOJAVE</div>
            <div style="display: flex; flex-direction: column; gap: 4px;">
                @foreach($intervencija->dojave as $d)
                    <button wire:click="odaberi('dojava', {{ $d->id }})"
                            style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 6px 10px; cursor: pointer; text-align: left; display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 11px; font-weight: 700; color: #475569;">#{{ $d->broj_dojave }}</span>
                        <span style="font-size: 11px; color: #0F172A; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $d->adresa }}</span>
                        <span style="font-size: 10px; color: #94A3B8;">{{ $d->vrijeme_zaprimanja->format('H:i') }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    @endif
</div>

{{-- FOOTER S AKCIJAMA --}}
<div style="padding: 12px 16px; background: #F8FAFC; border-top: 1px solid #E2E8F0; flex-shrink: 0; display: flex; gap: 8px;">
    <a href="/admin/intervencijas/{{ $intervencija->id }}/edit"
       style="flex: 1; background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%); color: white; padding: 13px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 800; text-align: center; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);">
        🎯 Vodi intervenciju
    </a>
</div>
