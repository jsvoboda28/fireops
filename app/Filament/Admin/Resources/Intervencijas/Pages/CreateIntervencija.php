<?php

namespace App\Filament\Admin\Resources\Intervencijas\Pages;

use App\Filament\Admin\Resources\Intervencijas\IntervencijaResource;
use App\Models\Intervencija;
use Filament\Resources\Pages\CreateRecord;

class CreateIntervencija extends CreateRecord
{
    protected static string $resource = IntervencijaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto broj intervencije
        if (empty($data['broj'])) {
            $brojDanas = Intervencija::whereDate('created_at', today())->count() + 1;
            $data['broj'] = now()->format('Y') . '-INT-' . str_pad($brojDanas, 6, '0', STR_PAD_LEFT);
        }

        // Auto vrijeme otvaranja
        if (empty($data['vrijeme_otvaranja'])) {
            $data['vrijeme_otvaranja'] = now();
        }

        // Auto voditelj
        if (empty($data['voditelj_id'])) {
            $data['voditelj_id'] = auth()->id();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}