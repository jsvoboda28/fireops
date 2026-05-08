<?php

namespace App\Filament\Admin\Resources\Vozilos;

use App\Filament\Admin\Resources\Vozilos\Pages\CreateVozilo;
use App\Filament\Admin\Resources\Vozilos\Pages\EditVozilo;
use App\Filament\Admin\Resources\Vozilos\Pages\ListVozilos;
use App\Filament\Admin\Resources\Vozilos\Pages\ViewVozilo;
use App\Filament\Admin\Resources\Vozilos\Schemas\VoziloForm;
use App\Filament\Admin\Resources\Vozilos\Schemas\VoziloInfolist;
use App\Filament\Admin\Resources\Vozilos\Tables\VozilosTable;
use App\Models\Vozilo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VoziloResource extends Resource
{
    protected static ?string $model = Vozilo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static ?string $recordTitleAttribute = 'registracija';

    // Hrvatski nazivi
    protected static ?string $modelLabel = 'Vozilo';
    protected static ?string $pluralModelLabel = 'Vozila';
    protected static ?string $navigationLabel = 'Vozila';
    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return VoziloForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VoziloInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VozilosTable::configure($table);
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
            'index' => ListVozilos::route('/'),
            'create' => CreateVozilo::route('/create'),
            'view' => ViewVozilo::route('/{record}'),
            'edit' => EditVozilo::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['registracija', 'marka', 'model'];
    }
}