<?php

namespace App\Filament\Admin\Resources\Vatrogasacs;

use App\Filament\Admin\Resources\Vatrogasacs\Pages\CreateVatrogasac;
use App\Filament\Admin\Resources\Vatrogasacs\Pages\EditVatrogasac;
use App\Filament\Admin\Resources\Vatrogasacs\Pages\ListVatrogasacs;
use App\Filament\Admin\Resources\Vatrogasacs\Pages\ViewVatrogasac;
use App\Filament\Admin\Resources\Vatrogasacs\Schemas\VatrogasacForm;
use App\Filament\Admin\Resources\Vatrogasacs\Schemas\VatrogasacInfolist;
use App\Filament\Admin\Resources\Vatrogasacs\Tables\VatrogasacsTable;
use App\Models\Vatrogasac;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class VatrogasacResource extends Resource
{
    protected static ?string $model = Vatrogasac::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'prezime';

    protected static ?string $modelLabel = 'Vatrogasac';
    protected static ?string $pluralModelLabel = 'Vatrogasci';
    protected static ?string $navigationLabel = 'Vatrogasci';
    protected static ?int $navigationSort = 2;
    
    protected static string|UnitEnum|null $navigationGroup = 'Resursi';

    public static function form(Schema $schema): Schema
    {
        return VatrogasacForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VatrogasacInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VatrogasacsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVatrogasacs::route('/'),
            'create' => CreateVatrogasac::route('/create'),
            'view' => ViewVatrogasac::route('/{record}'),
            'edit' => EditVatrogasac::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['ime', 'prezime', 'oib'];
    }
}