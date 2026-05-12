<?php

namespace App\Filament\Admin\Resources\Dojavas\Pages;

use App\Filament\Admin\Resources\Dojavas\DojavaResource;
use App\Models\Dojava;
use Filament\Resources\Pages\CreateRecord;

class CreateDojava extends CreateRecord
{
    protected static string $resource = DojavaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto broj dojave (npr. 2026-001234)
        if (empty($data['broj_dojave'])) {
            $brojDanas = Dojava::whereDate('created_at', today())->count() + 1;
            $data['broj_dojave'] = now()->format('Y') . '-' . str_pad($brojDanas, 6, '0', STR_PAD_LEFT);
        }

        // Auto vrijeme zaprimanja ako nije postavljeno
        if (empty($data['vrijeme_zaprimanja'])) {
            $data['vrijeme_zaprimanja'] = now();
        }

        // Auto operater (trenutni korisnik)
        if (empty($data['operater_id'])) {
            $data['operater_id'] = auth()->id();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}