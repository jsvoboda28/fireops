<?php

namespace App\Filament\Admin\Resources\Dojavas\Pages;

use App\Filament\Admin\Resources\Dojavas\DojavaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDojava extends ViewRecord
{
    protected static string $resource = DojavaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
