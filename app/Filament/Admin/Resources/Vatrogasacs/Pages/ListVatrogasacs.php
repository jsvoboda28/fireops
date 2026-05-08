<?php

namespace App\Filament\Admin\Resources\Vatrogasacs\Pages;

use App\Filament\Admin\Resources\Vatrogasacs\VatrogasacResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVatrogasacs extends ListRecords
{
    protected static string $resource = VatrogasacResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
