<?php

namespace App\Filament\Admin\Resources\OperativniDogadjajs\Pages;

use App\Filament\Admin\Resources\OperativniDogadjajs\OperativniDogadjajResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOperativniDogadjaj extends ViewRecord
{
    protected static string $resource = OperativniDogadjajResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
