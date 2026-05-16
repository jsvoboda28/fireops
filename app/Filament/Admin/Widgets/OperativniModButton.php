<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class OperativniModButton extends Widget
{
    protected string $view = 'filament.admin.widgets.operativni-mod-button';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -1;
}