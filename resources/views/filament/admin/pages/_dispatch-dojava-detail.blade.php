@php
    $prio = match($dojava->prioritet) {
        'kriticna' => ['bg' => 'linear-gradient(135deg, #EF4444 0%, #991B1B 100%)', 'border' => '#DC2626', 'badge' => 'KRITIČNA'],
        'visoka' => ['bg' => 'linear-gradient(135deg, #F59E0B 0%, #D97706 100%)', 'border' => '#F59E0B', 'badge' => 'VISOKA'],
        default => ['bg' => 'linear-gradient(135deg, #10B981 0%, #047857 100%)', 'border' => '#10B981', 'badge' => 'STANDARDNA'],
    };
    $tipIkona = match($dojava->tip_nepogode) {
        'olujno_nevrijeme' => '⛈', 'poplava' => '🌊', 'pozar' => '🔥',
        'snijeg_led' => '❄', 'klizište' => '⛰', 'tuca' => '🧊',
        'potres' => '🌍', 'spasavanje' => '⛑', 'opasne_tvari' => '☣',
        default => '⚠',
    };
@endphp

<div style="background: {{ $prio['bg'] }}; padding: 16px 20px; color: white; flex-shrink: 0; position: relative;">
    <button wire:click="zatvoriDetalje"
            style="position: absolute; top: 12px; right: 12px; background: rgba(0,0,0,0.25); color: white; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 16px; font-weight: 800;">
        ✕
    </button>

    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
        <div style="width: 44px; height: 44px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
            {{ $tipIkona }}
        </div>
        <div>
            <div style="font-size: 9px; font-weight: 700; opacity: 0.9; text-transform: uppercase; letter-spacing: 1.5px;">DOJAVA • {{ $prio['badge'] }}</div>
            <div style="font-size: 20px; font-weight: 900; letter-spacing: -0.3px; line-height: 1.1; margin-top: 2px;">#{{ $dojava->broj_dojave }}</div>
        </div>
    </div>
    <div style="font-size: 13px; opacity: 0.95; font-weight: 600;">
        {{ ucfirst(str_replace('_', ' ', $dojava->tip_nepogode ?? '?')) }}
    </div>
</div>

<div class="fo-scroll" style="overflow-y: auto; flex: 1; padding: 16px 20px;">

    <div style="margin-bottom: 18px;">
        <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #94A3B8; margin-bottom: 6px;">📍 LOKACIJA</div>
        <div style="font-size: 15px; font-weight: 700; color: #0F172A; line-height: 1.4;">{{ $dojava->adresa }}</div>
        <div style="font-size: 12px; color: #64748B; margin-top: 2px;">{{ $dojava->jls?->naziv ?? '—' }}</div>
        @if($dojava->latitude && $dojava->longitude)
            <div style="font-size: 11px; color: #94A3B8; margin-top: 4px; font-variant-numeric: tabular-nums;">
                📌 {{ number_format($dojava->latitude, 6) }}, {{ number_format($dojava->longitude, 6) }}
            </div>
        @endif
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 18px;">
        <div>
            <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #94A3B8; margin-bottom: 6px;">🕐 ZAPRIMLJENO</div>
            <div style="font-size: 14px; font-weight: 700; color: #0F172A;">{{ $dojava->vrijeme_zaprimanja->format('d.m.Y H:i') }}</div>
            <div style="font-size: 11px; color: #64748B; margin-top: 2px;">prije {{ $dojava->vrijeme_zaprimanja->diffForHumans(null, true, true) }}</div>
        </div>
        <div>
            <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #94A3B8; margin-bottom: 6px;">STATUS</div>
            <div style="font-size: 14px; font-weight: 700; color: #0F172A; text-transform: capitalize;">{{ str_replace('_', ' ', $dojava->status) }}</div>
        </div>
    </div>

    {{-- BRZA PROMJENA STATUSA --}}
    <div style="margin-bottom: 18px;">
        <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #94A3B8; margin-bottom: 6px;">⚡ BRZA PROMJENA STATUSA</div>
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 4px;">
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
                <button wire:click="brzaPromjenaStatusaDojave('{{ $key }}')"
                        style="background: {{ $dojava->status === $key ? '#0F172A' : 'white' }}; color: {{ $dojava->status === $key ? 'white' : '#475569' }}; border: 1px solid {{ $dojava->status === $key ? '#0F172A' : '#E2E8F0' }}; padding: 6px 3px; border-radius: 5px; font-size: 9px; font-weight: 700; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px;">
                    <span style="font-size: 12px;">{{ $info[0] }}</span>
                    <span>{{ $info[1] }}</span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- BRZA PROMJENA PRIORITETA --}}
    <div style="margin-bottom: 18px;">
        <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #94A3B8; margin-bottom: 6px;">🎯 BRZA PROMJENA PRIORITETA</div>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px;">
            @php
                $prioOpcije = [
                    'kriticna' => ['🔴', 'KRITIČNA', '#DC2626'],
                    'visoka' => ['🟡', 'VISOKA', '#F59E0B'],
                    'standardna' => ['🟢', 'STANDARDNA', '#10B981'],
                ];
            @endphp
            @foreach($prioOpcije as $key => $info)
                <button wire:click="brzaPromjenaPrioritetaDojave('{{ $key }}')"
                        style="background: {{ $dojava->prioritet === $key ? $info[2] : 'white' }}; color: {{ $dojava->prioritet === $key ? 'white' : '#475569' }}; border: 2px solid {{ $dojava->prioritet === $key ? $info[2] : '#E2E8F0' }}; padding: 7px 4px; border-radius: 6px; cursor: pointer; text-align: center;">
                    <div style="font-size: 13px;">{{ $info[0] }}</div>
                    <div style="font-size: 9px; font-weight: 800; letter-spacing: 0.3px; margin-top: 2px;">{{ $info[1] }}</div>
                </button>
            @endforeach
        </div>
    </div>

    @if($dojava->opis)
        <div style="margin-bottom: 18px;">
            <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #94A3B8; margin-bottom: 6px;">📝 OPIS</div>
            <div style="font-size: 13px; color: #0F172A; line-height: 1.5; background: #F8FAFC; padding: 10px 12px; border-radius: 8px;">
                {{ $dojava->opis }}
            </div>
        </div>
    @endif

    @if($dojava->intervencija)
        <div style="background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%); border: 1px solid #FCA5A5; border-radius: 10px; padding: 14px; margin-bottom: 18px;">
            <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #991B1B; margin-bottom: 6px;">🔥 VEZANA INTERVENCIJA</div>
            <div style="font-size: 14px; font-weight: 700; color: #0F172A;">{{ $dojava->intervencija->naziv }}</div>
            <div style="font-size: 11px; color: #64748B; margin-top: 2px;">#{{ $dojava->intervencija->broj }}</div>
            <button wire:click="odaberi('intervencija', {{ $dojava->intervencija->id }})"
                    style="background: #DC2626; color: white; border: none; padding: 7px 14px; border-radius: 6px; font-size: 12px; font-weight: 800; cursor: pointer; margin-top: 10px;">
                Prikaži intervenciju →
            </button>
        </div>
    @endif

    <div style="margin-bottom: 18px;">
        <div style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.8px; color: #94A3B8; margin-bottom: 6px;">👤 ZAPRIMIO</div>
        <div style="font-size: 13px; font-weight: 700; color: #0F172A;">{{ $dojava->operater?->name ?? '—' }}</div>
    </div>
</div>

<div style="padding: 12px 16px; background: #F8FAFC; border-top: 1px solid #E2E8F0; flex-shrink: 0; display: flex; gap: 8px;">
    <button wire:click="otvoriUrediDojavu({{ $dojava->id }})"
            style="flex: 1; background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%); color: white; padding: 11px; border: none; border-radius: 8px; font-size: 13px; font-weight: 800; cursor: pointer; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);">
        ✏ Uredi dojavu
    </button>
    @if(!$dojava->intervencija)
        <button wire:click="otvoriIntervencijuIzDojave"
                style="flex: 1; background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%); color: white; padding: 11px; border: none; border-radius: 8px; font-size: 13px; font-weight: 800; cursor: pointer; box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);">
            🔥 Otvori intervenciju
        </button>
    @endif
</div>
