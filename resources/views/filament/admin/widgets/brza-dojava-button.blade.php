<x-filament-widgets::widget>
    <div style="display: flex; justify-content: center; padding: 16px 0;">
        <div style="position: relative;">
            <span style="position: absolute; inset: -8px; border-radius: 16px; background: linear-gradient(135deg, #DC2626, #F97316); opacity: 0.4; filter: blur(12px); animation: pulse-ring 2s cubic-bezier(0, 0, 0.2, 1) infinite;"></span>
            
            <a href="{{ route('filament.admin.resources.dojavas.create') }}"
               style="position: relative; display: inline-flex; align-items: center; gap: 12px; padding: 18px 36px; background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%); color: white; font-size: 18px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; border-radius: 12px; text-decoration: none; box-shadow: 0 10px 25px rgba(220, 38, 38, 0.4); transition: all 0.2s;"
               onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 15px 35px rgba(220, 38, 38, 0.5)';"
               onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 10px 25px rgba(220, 38, 38, 0.4)';">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                    <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                </svg>
                Brza dojava
            </a>
        </div>
    </div>

    <style>
        @keyframes pulse-ring {
            0%, 100% { transform: scale(1); opacity: 0.4; }
            50% { transform: scale(1.05); opacity: 0.7; }
        }
    </style>
</x-filament-widgets::widget>