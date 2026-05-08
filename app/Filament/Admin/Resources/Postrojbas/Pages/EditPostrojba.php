<?php

namespace App\Filament\Admin\Resources\Postrojbas\Pages;

use App\Filament\Admin\Resources\Postrojbas\PostrojbaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPostrojba extends EditRecord
{
    protected static string $resource = PostrojbaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
