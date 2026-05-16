<div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); z-index: 5000; display: flex; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(4px);"
     wire:click.self="zatvoriModal">
    <div style="background: white; border-radius: 14px; box-shadow: 0 24px 48px rgba(0,0,0,0.25); width: 100%; max-width: 480px; max-height: 90vh; overflow: hidden; display: flex; flex-direction: column;">
        <div style="padding: 16px 20px; background: linear-gradient(135deg, #10B981 0%, #047857 100%); color: white; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 9px; font-weight: 700; opacity: 0.9; text-transform: uppercase; letter-spacing: 1px;">FORMIRANJE TIMA</div>
                <div style="font-size: 18px; font-weight: 900; margin-top: 2px;">➕ Novi tim</div>
            </div>
            <button wire:click="zatvoriModal" style="background: rgba(0,0,0,0.2); color: white; border: none; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; font-size: 14px;">✕</button>
        </div>
        <div style="padding: 18px 20px; overflow-y: auto; flex: 1;">
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #475569; margin-bottom: 5px;">Naziv tima *</label>
                <input type="text" wire:model="modalData.naziv" placeholder="npr. Tim Kaptol-1" class="fo-input" style="font-size: 13px;">
            </div>
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #475569; margin-bottom: 5px;">Bazna postrojba *</label>
                <select wire:model="modalData.baza_postrojba_id" class="fo-input" style="font-size: 13px;">
                    <option value="">-- Odaberi postrojbu --</option>
                    @foreach($this->postrojbeOpcije as $id => $naziv)
                        <option value="{{ $id }}">{{ $naziv }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #475569; margin-bottom: 5px;">Zapovjednik *</label>
                <select wire:model="modalData.zapovjednik_id" class="fo-input" style="font-size: 13px;">
                    <option value="">-- Odaberi zapovjednika --</option>
                    @foreach($this->vatrogasciOpcije as $id => $naziv)
                        <option value="{{ $id }}">{{ $naziv }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; color: #475569; margin-bottom: 5px;">Zadatak (opcionalno)</label>
                <textarea wire:model="modalData.zadatak" rows="2" class="fo-input" style="font-size: 13px; resize: vertical;"></textarea>
            </div>
        </div>
        <div style="padding: 12px 20px; background: #F8FAFC; border-top: 1px solid #E2E8F0; display: flex; gap: 8px;">
            <button wire:click="kreirajTim" style="flex: 1; background: linear-gradient(135deg, #10B981 0%, #047857 100%); color: white; border: none; padding: 11px; border-radius: 8px; font-size: 13px; font-weight: 800; cursor: pointer;">
                ➕ Formiraj tim
            </button>
            <button wire:click="zatvoriModal" style="background: white; border: 1px solid #E2E8F0; color: #475569; padding: 11px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer;">
                Odustani
            </button>
        </div>
    </div>
</div>
