<?php

namespace App\Filament\Admin\Resources\VatrogasnaZajednicas\Pages;

use App\Filament\Admin\Resources\VatrogasnaZajednicas\VatrogasnaZajednicaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditVatrogasnaZajednica extends EditRecord
{
    protected static string $resource = VatrogasnaZajednicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
