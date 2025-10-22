<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpertResource\Pages;
use App\Filament\Resources\ExpertResource\Schemas\ExpertFormSchema;
use App\Filament\Resources\ExpertResource\Schemas\ExpertInfolistSchema;
use App\Filament\Resources\ExpertResource\Tables\ExpertTable;
use App\Models\Expert;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;

class ExpertResource extends Resource
{
    protected static ?string $model = Expert::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    
    protected static ?string $navigationGroup = 'User Management';
    
    protected static ?int $navigationSort = 2;
    
    public static function form(Form $form): Form
    {
        return $form->schema(ExpertFormSchema::getSchema());
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns(ExpertTable::getColumns())
            ->filters(ExpertTable::getFilters())
            ->actions(ExpertTable::getActions())
            ->bulkActions(ExpertTable::getBulkActions())
            ->defaultSort('created_at', 'desc');
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(ExpertInfolistSchema::getSchema());
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
            'index' => Pages\ListExperts::route('/'),
            'create' => Pages\CreateExpert::route('/create'),
            'view' => Pages\ViewExpert::route('/{record}'),
            'edit' => Pages\EditExpert::route('/{record}/edit'),
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
