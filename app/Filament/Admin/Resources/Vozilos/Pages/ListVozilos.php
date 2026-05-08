<?php

namespace App\Filament\Admin\Resources\Vozilos\Pages;

use App\Filament\Admin\Resources\Vozilos\VoziloResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVozilos extends ListRecords
{
    protected static string $resource = VoziloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
