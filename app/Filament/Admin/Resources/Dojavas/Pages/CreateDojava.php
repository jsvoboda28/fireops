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
        if (empty($data['broj_dojave'])) {
            $godina = now()->format('Y');
            
            // Pronađi zadnji broj dojave ove godine
            $zadnja = Dojava::where('broj_dojave', 'LIKE', $godina . '-%')
                ->orderByRaw("CAST(SPLIT_PART(broj_dojave, '-', 2) AS INTEGER) DESC")
                ->first();
            
            $sljedeci = 1;
            if ($zadnja) {
                $parts = explode('-', $zadnja->broj_dojave);
                $sljedeci = (int) end($parts) + 1;
            }
            
            $data['broj_dojave'] = $godina . '-' . str_pad($sljedeci, 6, '0', STR_PAD_LEFT);
        }

        if (empty($data['vrijeme_zaprimanja'])) {
            $data['vrijeme_zaprimanja'] = now();
        }

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