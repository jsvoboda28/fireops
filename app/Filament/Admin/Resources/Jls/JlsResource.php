<?php

namespace App\Filament\Admin\Resources\Jls;

use App\Filament\Admin\Resources\Jls\Pages\CreateJls;
use App\Filament\Admin\Resources\Jls\Pages\EditJls;
use App\Filament\Admin\Resources\Jls\Pages\ListJls;
use App\Filament\Admin\Resources\Jls\RelationManagers\PostrojbeRelationManager;
use App\Filament\Admin\Resources\Jls\Schemas\JlsForm;
use App\Filament\Admin\Resources\Jls\Tables\JlsTable;
use App\Models\Jls;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JlsResource extends Resource
{
    protected static ?string $model = Jls::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static ?string $recordTitleAttribute = 'naziv';

    // Hrvatski nazivi
    protected static ?string $modelLabel = 'JLS';
    protected static ?string $pluralModelLabel = 'JLS-ovi';
    protected static ?string $navigationLabel = 'JLS-ovi';
    protected static ?int $navigationSort = 10;

    // Grupiranje u sidebaru
    protected static \UnitEnum|string|null $navigationGroup = 'Postavke sustava';

    public static function form(Schema $schema): Schema
    {
        return JlsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JlsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PostrojbeRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJls::route('/'),
            'create' => CreateJls::route('/create'),
            'edit' => EditJls::route('/{record}/edit'),
        ];
    }
}