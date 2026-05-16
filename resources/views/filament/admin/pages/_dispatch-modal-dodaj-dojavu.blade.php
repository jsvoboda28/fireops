<div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); z-index: 5000; display: flex; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(4px);"
     wire:click.self="zatvoriModal">
    <div style="background: white; border-radius: 14px; box-shadow: 0 24px 48px rgba(0,0,0,0.25); width: 100%; max-width: 520px; max-height: 90vh; overflow: hidden; display: flex; flex-direction: column;">
        <div style="padding: 16px 20px; background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%); color: white; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 9px; font-weight: 700; opacity: 0.9; text-transform: uppercase; letter-spacing: 1px;">SPAJANJE DOJAVE</div>
                <div style="font-size: 18px; font-weight: 900; margin-top: 2px;">📞 Dodaj dojavu</div>
            </div>
            <button wire:click="zatvoriModal" style="background: rgba(0,0,0,0.2); color: white; border: none; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; font-size: 14px;">✕</button>
        </div>
        <div style="padding: 18px 20px; overflow-y: auto; flex: 1;">
            <div style="font-size: 12px; color: #64748B; margin-bottom: 12px;">Prikazane su sve dojave bez intervencije.</div>
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #475569; margin-bottom: 5px;">Dojava *</label>
                <select wire:model="modalData.dojava_id" class="fo-input" style="font-size: 12px;">
                    <option value="">-- Odaberi dojavu --</option>
                    @foreach($this->slobodneDojaveOpcije as $id => $naziv)
                        <option value="{{ $id }}">{{ $naziv }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div style="padding: 12px 20px; background: #F8FAFC; border-top: 1px solid #E2E8F0; display: flex; gap: 8px;">
            <button wire:click="dodajDojavuUIntervenciju" style="flex: 1; background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%); color: white; border: none; padding: 11px; border-radius: 8px; font-size: 13px; font-weight: 800; cursor: pointer;">
                📞 Dodaj u intervenciju
            </button>
            <button wire:click="zatvoriModal" style="background: white; border: 1px solid #E2E8F0; color: #475569; padding: 11px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer;">
                Odustani
            </button>
        </div>
    </div>
</div>
