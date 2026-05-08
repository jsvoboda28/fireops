<?php

namespace App\Filament\Admin\Resources\Postrojbas\Pages;

use App\Filament\Admin\Resources\Postrojbas\PostrojbaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPostrojbas extends ListRecords
{
    protected static string $resource = PostrojbaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
