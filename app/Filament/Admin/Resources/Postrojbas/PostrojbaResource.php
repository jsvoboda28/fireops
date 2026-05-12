<?php

namespace App\Filament\Admin\Resources\Postrojbas;

use App\Filament\Admin\Resources\Postrojbas\Pages\CreatePostrojba;
use App\Filament\Admin\Resources\Postrojbas\Pages\EditPostrojba;
use App\Filament\Admin\Resources\Postrojbas\Pages\ListPostrojbas;
use App\Filament\Admin\Resources\Postrojbas\Pages\ViewPostrojba;
use App\Filament\Admin\Resources\Postrojbas\RelationManagers\VatrogasciRelationManager;
use App\Filament\Admin\Resources\Postrojbas\RelationManagers\VozilaRelationManager;
use App\Filament\Admin\Resources\Postrojbas\Schemas\PostrojbaForm;
use App\Filament\Admin\Resources\Postrojbas\Schemas\PostrojbaInfolist;
use App\Filament\Admin\Resources\Postrojbas\Tables\PostrojbasTable;
use App\Models\Postrojba;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PostrojbaResource extends Resource
{
    protected static ?string $model = Postrojba::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $recordTitleAttribute = 'naziv';

    protected static ?string $modelLabel = 'Postrojba';
    protected static ?string $pluralModelLabel = 'Postrojbe';
    protected static ?string $navigationLabel = 'Postrojbe';
    protected static ?int $navigationSort = 1;
    
    protected static string|UnitEnum|null $navigationGroup = 'Resursi';

    public static function form(Schema $schema): Schema
    {
        return PostrojbaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PostrojbaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PostrojbasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            VatrogasciRelationManager::class,
            VozilaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPostrojbas::route('/'),
            'create' => CreatePostrojba::route('/create'),
            'view' => ViewPostrojba::route('/{record}'),
            'edit' => EditPostrojba::route('/{record}/edit'),
        ];
    }
}