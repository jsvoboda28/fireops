<?php

namespace App\Filament\Admin\Resources\Dojavas;

use App\Filament\Admin\Resources\Dojavas\Pages\CreateDojava;
use App\Filament\Admin\Resources\Dojavas\Pages\EditDojava;
use App\Filament\Admin\Resources\Dojavas\Pages\ListDojavas;
use App\Filament\Admin\Resources\Dojavas\Pages\ViewDojava;
use App\Filament\Admin\Resources\Dojavas\Schemas\DojavaForm;
use App\Filament\Admin\Resources\Dojavas\Schemas\DojavaInfolist;
use App\Filament\Admin\Resources\Dojavas\Tables\DojavasTable;
use App\Models\Dojava;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DojavaResource extends Resource
{
    protected static ?string $model = Dojava::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static ?string $recordTitleAttribute = 'broj_dojave';

    // Hrvatski nazivi
    protected static ?string $modelLabel = 'Dojava';
    protected static ?string $pluralModelLabel = 'Dojave';
    protected static ?string $navigationLabel = 'Dojave';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return DojavaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DojavaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DojavasTable::configure($table);
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
            'index' => ListDojavas::route('/'),
            'create' => CreateDojava::route('/create'),
            'view' => ViewDojava::route('/{record}'),
            'edit' => EditDojava::route('/{record}/edit'),
        ];
    }
}