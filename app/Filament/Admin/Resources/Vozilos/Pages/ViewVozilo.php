<?php

namespace App\Filament\Admin\Resources\Vozilos\Pages;

use App\Filament\Admin\Resources\Vozilos\VoziloResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVozilo extends ViewRecord
{
    protected static string $resource = VoziloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
