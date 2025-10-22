<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentCreatorResource\Pages;
use App\Filament\Resources\ContentCreatorResource\Schemas\ContentCreatorFormSchema;
use App\Filament\Resources\ContentCreatorResource\Schemas\ContentCreatorInfolistSchema;
use App\Filament\Resources\ContentCreatorResource\Tables\ContentCreatorTable;
use App\Models\ContentCreator;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;

class ContentCreatorResource extends Resource
{
    protected static ?string $model = ContentCreator::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Content Creators';

    public static function form(Form $form): Form
    {
        return $form->schema(ContentCreatorFormSchema::getSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(ContentCreatorTable::getColumns())
            ->filters(ContentCreatorTable::getFilters())
            ->actions(ContentCreatorTable::getActions())
            ->bulkActions(ContentCreatorTable::getBulkActions())
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(ContentCreatorInfolistSchema::getSchema());
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
            'index' => Pages\ListContentCreators::route('/'),
            'create' => Pages\CreateContentCreator::route('/create'),
            'view' => Pages\ViewContentCreator::route('/{record}'),
            'edit' => Pages\EditContentCreator::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('application_status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
