<?php

namespace App\Filament\Admin\Resources\Intervencijas;

use App\Filament\Admin\Resources\Intervencijas\Pages\CreateIntervencija;
use App\Filament\Admin\Resources\Intervencijas\Pages\EditIntervencija;
use App\Filament\Admin\Resources\Intervencijas\Pages\ListIntervencijas;
use App\Filament\Admin\Resources\Intervencijas\Schemas\IntervencijaForm;
use App\Filament\Admin\Resources\Intervencijas\Tables\IntervencijasTable;
use App\Models\Intervencija;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class IntervencijaResource extends Resource
{
    protected static ?string $model = Intervencija::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFire;

    protected static ?string $recordTitleAttribute = 'broj';

    protected static ?string $modelLabel = 'Intervencija';
    protected static ?string $pluralModelLabel = 'Intervencije';
    protected static ?string $navigationLabel = 'Intervencije';
    protected static ?int $navigationSort = 0;

    protected static string|UnitEnum|null $navigationGroup = 'Operativa';

    public static function form(Schema $schema): Schema
    {
        return IntervencijaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IntervencijasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIntervencijas::route('/'),
            'create' => CreateIntervencija::route('/create'),
            'edit' => EditIntervencija::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $aktivne = Intervencija::where('status', 'aktivna')->count();
        return $aktivne > 0 ? (string) $aktivne : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}