<?php

namespace App\Filament\Admin\Resources\OperativniDogadjajs\Pages;

use App\Filament\Admin\Resources\OperativniDogadjajs\OperativniDogadjajResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOperativniDogadjaj extends EditRecord
{
    protected static string $resource = OperativniDogadjajResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
