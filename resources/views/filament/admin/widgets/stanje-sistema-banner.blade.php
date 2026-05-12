<x-filament-widgets::widget>
    @php
        $gradient = match($boja) {
            'red' => 'linear-gradient(135deg, #DC2626 0%, #991B1B 100%)',
            'orange' => 'linear-gradient(135deg, #F97316 0%, #DC2626 100%)',
            'yellow' => 'linear-gradient(135deg, #EAB308 0%, #F97316 100%)',
            default => 'linear-gradient(135deg, #059669 0%, #065F46 100%)',
        };
        $pingColor = match($boja) {
            'red' => '#FCA5A5',
            'orange' => '#FED7AA',
            'yellow' => '#FDE68A',
            default => '#A7F3D0',
        };
    @endphp

    <div style="border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.15); background: {{ $gradient }}; color: white;">
        
        <div style="padding: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
            
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="position: relative; width: 16px; height: 16px;">
                    <span style="position: absolute; inset: 0; border-radius: 50%; background: {{ $pingColor }}; opacity: 0.75; animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;"></span>
                    <span style="position: relative; display: block; width: 16px; height: 16px; border-radius: 50%; background: white;"></span>
                </div>
                
                <div>
                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; opacity: 0.9;">
                        FireOps PSŽ • Stanje sustava
                    </div>
                    <div style="font-size: 28px; font-weight: 900; margin-top: 4px; line-height: 1.1;">
                        {{ $naslov }}
                    </div>
                    <div style="font-size: 14px; opacity: 0.95; margin-top: 6px;">
                        {{ $opis }}
                    </div>
                </div>
            </div>
            
            <div style="text-align: right;"
                 x-data="{ vrijeme: new Date().toLocaleTimeString('hr-HR', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }"
                 x-init="setInterval(() => { vrijeme = new Date().toLocaleTimeString('hr-HR', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)">
                <div style="font-size: 42px; font-weight: 900; font-variant-numeric: tabular-nums; line-height: 1;" x-text="vrijeme">
                </div>
                <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; opacity: 0.9; margin-top: 6px;">
                    {{ $datum }}
                </div>
            </div>
        </div>
        
        <div style="background: rgba(0,0,0,0.2); padding: 14px 24px; display: flex; align-items: center; gap: 24px; font-size: 12px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 24px; font-weight: 900;">{{ $aktivnihDogadjaja }}</span>
                <span style="text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; font-weight: 600;">Aktivnih događaja</span>
            </div>
            <div style="height: 24px; width: 1px; background: rgba(255,255,255,0.3);"></div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 24px; font-weight: 900;">{{ $pracenjeDogadjaja }}</span>
                <span style="text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; font-weight: 600;">U praćenju</span>
            </div>
            <div style="height: 24px; width: 1px; background: rgba(255,255,255,0.3);"></div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 24px; font-weight: 900;">{{ $kritickihDojava }}</span>
                <span style="text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; font-weight: 600;">Kritičnih dojava</span>
            </div>
        </div>
    </div>

    <style>
        @keyframes ping {
            75%, 100% {
                transform: scale(2);
                opacity: 0;
            }
        }
    </style>
</x-filament-widgets::widget>