<?php

namespace App\Filament\Admin\Resources\VatrogasnaZajednicas;

use App\Filament\Admin\Resources\VatrogasnaZajednicas\Pages\CreateVatrogasnaZajednica;
use App\Filament\Admin\Resources\VatrogasnaZajednicas\Pages\EditVatrogasnaZajednica;
use App\Filament\Admin\Resources\VatrogasnaZajednicas\Pages\ListVatrogasnaZajednicas;
use App\Filament\Admin\Resources\VatrogasnaZajednicas\Pages\ViewVatrogasnaZajednica;
use App\Filament\Admin\Resources\VatrogasnaZajednicas\Schemas\VatrogasnaZajednicaForm;
use App\Filament\Admin\Resources\VatrogasnaZajednicas\Schemas\VatrogasnaZajednicaInfolist;
use App\Filament\Admin\Resources\VatrogasnaZajednicas\Tables\VatrogasnaZajednicasTable;
use App\Models\VatrogasnaZajednica;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VatrogasnaZajednicaResource extends Resource
{
    protected static ?string $model = VatrogasnaZajednica::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $recordTitleAttribute = 'naziv';

    // Hrvatski nazivi
    protected static ?string $modelLabel = 'Vatrogasna zajednica';
    protected static ?string $pluralModelLabel = 'Vatrogasne zajednice';
    protected static ?string $navigationLabel = 'Vatrogasne zajednice';
    protected static ?int $navigationSort = 11;

    // Grupiranje u sidebaru
    protected static \UnitEnum|string|null $navigationGroup = 'Postavke sustava';

    public static function form(Schema $schema): Schema
    {
        return VatrogasnaZajednicaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VatrogasnaZajednicaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VatrogasnaZajednicasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVatrogasnaZajednicas::route('/'),
            'create' => CreateVatrogasnaZajednica::route('/create'),
            'view' => ViewVatrogasnaZajednica::route('/{record}'),
            'edit' => EditVatrogasnaZajednica::route('/{record}/edit'),
        ];
    }
}