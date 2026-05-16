<?php

namespace App\Filament\Admin\Resources\Tims;

use App\Filament\Admin\Resources\Tims\Pages\CreateTim;
use App\Filament\Admin\Resources\Tims\Pages\EditTim;
use App\Filament\Admin\Resources\Tims\Pages\ListTims;
use App\Filament\Admin\Resources\Tims\Schemas\TimForm;
use App\Filament\Admin\Resources\Tims\Tables\TimsTable;
use App\Models\Tim;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TimResource extends Resource
{
    protected static ?string $model = Tim::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $recordTitleAttribute = 'naziv';

    protected static ?string $modelLabel = 'Tim';
    protected static ?string $pluralModelLabel = 'Timovi';
    protected static ?string $navigationLabel = 'Timovi';
    protected static ?int $navigationSort = 3;

    protected static string|UnitEnum|null $navigationGroup = 'Operativa';

    public static function form(Schema $schema): Schema
    {
        return TimForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TimsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTims::route('/'),
            'create' => CreateTim::route('/create'),
            'edit' => EditTim::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $aktivni = Tim::where('trenutni_status', '!=', 'raspusten')->count();
        return $aktivni > 0 ? (string) $aktivni : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}