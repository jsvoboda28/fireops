<?php

namespace App\Filament\Admin\Resources\Postrojbas\Pages;

use App\Filament\Admin\Resources\Postrojbas\PostrojbaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPostrojba extends ViewRecord
{
    protected static string $resource = PostrojbaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
