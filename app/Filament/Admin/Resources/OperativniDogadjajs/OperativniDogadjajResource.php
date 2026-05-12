<?php

namespace App\Filament\Admin\Resources\OperativniDogadjajs;

use App\Filament\Admin\Resources\OperativniDogadjajs\Pages\CreateOperativniDogadjaj;
use App\Filament\Admin\Resources\OperativniDogadjajs\Pages\EditOperativniDogadjaj;
use App\Filament\Admin\Resources\OperativniDogadjajs\Pages\ListOperativniDogadjajs;
use App\Filament\Admin\Resources\OperativniDogadjajs\Pages\ViewOperativniDogadjaj;
use App\Filament\Admin\Resources\OperativniDogadjajs\RelationManagers\DojaveRelationManager;
use App\Filament\Admin\Resources\OperativniDogadjajs\Schemas\OperativniDogadjajForm;
use App\Filament\Admin\Resources\OperativniDogadjajs\Schemas\OperativniDogadjajInfolist;
use App\Filament\Admin\Resources\OperativniDogadjajs\Tables\OperativniDogadjajsTable;
use App\Models\OperativniDogadjaj;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class OperativniDogadjajResource extends Resource
{
    protected static ?string $model = OperativniDogadjaj::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFire;

    protected static ?string $recordTitleAttribute = 'naziv';

    protected static ?string $modelLabel = 'Operativni događaj';
    protected static ?string $pluralModelLabel = 'Operativni događaji';
    protected static ?string $navigationLabel = 'Operativni događaji';
    protected static ?int $navigationSort = 2;
    
    protected static string|UnitEnum|null $navigationGroup = 'Operativa';

    public static function form(Schema $schema): Schema
    {
        return OperativniDogadjajForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OperativniDogadjajInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OperativniDogadjajsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DojaveRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOperativniDogadjajs::route('/'),
            'create' => CreateOperativniDogadjaj::route('/create'),
            'view' => ViewOperativniDogadjaj::route('/{record}'),
            'edit' => EditOperativniDogadjaj::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $aktivnih = OperativniDogadjaj::where('status', 'aktivan')->count();
        return $aktivnih > 0 ? (string) $aktivnih : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}