<?php

namespace App\Filament\Admin\Resources\Tims\Pages;

use App\Filament\Admin\Resources\Tims\TimResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTim extends EditRecord
{
    protected static string $resource = TimResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
