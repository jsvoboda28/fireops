<?php

namespace App\Filament\Admin\Resources\Vozilos\Pages;

use App\Filament\Admin\Resources\Vozilos\VoziloResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditVozilo extends EditRecord
{
    protected static string $resource = VoziloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
