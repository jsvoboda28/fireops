<x-filament-widgets::widget>
    @php
        $bojaMap = [
            'red' => ['bg' => 'linear-gradient(135deg, #DC2626 0%, #991B1B 100%)', 'icon_bg' => 'rgba(255,255,255,0.2)'],
            'orange' => ['bg' => 'linear-gradient(135deg, #F97316 0%, #C2410C 100%)', 'icon_bg' => 'rgba(255,255,255,0.2)'],
            'emerald' => ['bg' => 'linear-gradient(135deg, #059669 0%, #047857 100%)', 'icon_bg' => 'rgba(255,255,255,0.2)'],
            'blue' => ['bg' => 'linear-gradient(135deg, #2563EB 0%, #1E40AF 100%)', 'icon_bg' => 'rgba(255,255,255,0.2)'],
            'gray' => ['bg' => 'linear-gradient(135deg, #64748B 0%, #475569 100%)', 'icon_bg' => 'rgba(255,255,255,0.2)'],
        ];
        $iconMap = [
            'fire' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="32" height="32"><path fill-rule="evenodd" d="M12.963 2.286a.75.75 0 0 0-1.071-.136 9.742 9.742 0 0 0-3.539 6.176 7.547 7.547 0 0 1-1.705-1.715.75.75 0 0 0-1.152-.082A9 9 0 1 0 15.68 4.534a7.46 7.46 0 0 1-2.717-2.248ZM15.75 14.25a3.75 3.75 0 1 1-7.313-1.172c.628.465 1.35.81 2.133 1a5.99 5.99 0 0 1 1.925-3.546 3.75 3.75 0 0 1 3.255 3.718Z" clip-rule="evenodd" /></svg>',
            'bell' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="32" height="32"><path fill-rule="evenodd" d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Zm4.502 8.9a2.25 2.25 0 1 0 4.496 0 25.057 25.057 0 0 1-4.496 0Z" clip-rule="evenodd" /></svg>',
            'shield' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="32" height="32"><path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" /></svg>',
            'users' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="32" height="32"><path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" /></svg>',
        ];
    @endphp

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
        @foreach($kartice as $kartica)
            @php
                $styles = $bojaMap[$kartica['boja']] ?? $bojaMap['gray'];
            @endphp
            <div style="border-radius: 12px; padding: 20px; background: {{ $styles['bg'] }}; color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.1); position: relative; overflow: hidden;">
                
                <!-- Pozadinska ikona dekorativna -->
                <div style="position: absolute; top: -10px; right: -10px; opacity: 0.15; transform: rotate(-15deg);">
                    {!! $iconMap[$kartica['icon']] ?? '' !!}
                </div>
                
                <!-- Mala ikona u kutu -->
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <div style="background: {{ $styles['icon_bg'] }}; padding: 8px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        {!! $iconMap[$kartica['icon']] ?? '' !!}
                    </div>
                    <div style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; opacity: 0.95;">
                        {{ $kartica['naslov'] }}
                    </div>
                </div>
                
                <!-- Veliki broj -->
                <div style="font-size: 48px; font-weight: 900; line-height: 1; margin: 8px 0; font-variant-numeric: tabular-nums;">
                    {{ $kartica['broj'] }}
                </div>
                
                <!-- Podnaslov -->
                <div style="font-size: 12px; opacity: 0.9; font-weight: 500;">
                    {{ $kartica['podnaslov'] }}
                </div>
            </div>
        @endforeach
    </div>
</x-filament-widgets::widget>