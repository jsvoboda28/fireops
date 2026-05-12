<?php

namespace App\Filament\Admin\Resources\VatrogasnaZajednicas\Pages;

use App\Filament\Admin\Resources\VatrogasnaZajednicas\VatrogasnaZajednicaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVatrogasnaZajednica extends ViewRecord
{
    protected static string $resource = VatrogasnaZajednicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
