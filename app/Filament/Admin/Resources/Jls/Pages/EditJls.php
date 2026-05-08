<?php

namespace App\Filament\Admin\Resources\Jls\Pages;

use App\Filament\Admin\Resources\Jls\JlsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJls extends EditRecord
{
    protected static string $resource = JlsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
