<?php

namespace App\Filament\Admin\Resources\Vatrogasacs\Pages;

use App\Filament\Admin\Resources\Vatrogasacs\VatrogasacResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditVatrogasac extends EditRecord
{
    protected static string $resource = VatrogasacResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
