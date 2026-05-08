<?php

namespace App\Filament\Admin\Resources\OperativniDogadjajs\Pages;

use App\Filament\Admin\Resources\OperativniDogadjajs\OperativniDogadjajResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOperativniDogadjajs extends ListRecords
{
    protected static string $resource = OperativniDogadjajResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
