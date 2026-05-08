<?php

namespace App\Filament\Admin\Resources\Vatrogasacs\Pages;

use App\Filament\Admin\Resources\Vatrogasacs\VatrogasacResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVatrogasac extends ViewRecord
{
    protected static string $resource = VatrogasacResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
