<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class BrzaDojavaButton extends Widget
{
    protected string $view = 'filament.admin.widgets.brza-dojava-button';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -2;
}