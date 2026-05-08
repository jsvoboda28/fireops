<?php

namespace App\Filament\Admin\Resources\Dojavas\Pages;

use App\Filament\Admin\Resources\Dojavas\DojavaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDojava extends EditRecord
{
    protected static string $resource = DojavaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
