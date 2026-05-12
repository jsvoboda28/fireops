<?php

namespace App\Filament\Admin\Resources\VatrogasnaZajednicas\Pages;

use App\Filament\Admin\Resources\VatrogasnaZajednicas\VatrogasnaZajednicaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVatrogasnaZajednicas extends ListRecords
{
    protected static string $resource = VatrogasnaZajednicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
