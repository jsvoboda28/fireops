<?php

namespace App\Filament\Admin\Resources\Jls\Pages;

use App\Filament\Admin\Resources\Jls\JlsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJls extends ListRecords
{
    protected static string $resource = JlsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
