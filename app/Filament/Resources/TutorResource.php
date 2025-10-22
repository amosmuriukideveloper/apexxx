<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TutorResource\Pages;
use App\Filament\Resources\TutorResource\Schemas\TutorFormSchema;
use App\Filament\Resources\TutorResource\Schemas\TutorInfolistSchema;
use App\Filament\Resources\TutorResource\Tables\TutorTable;
use App\Models\Tutor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;

class TutorResource extends Resource
{
    protected static ?string $model = Tutor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema(TutorFormSchema::getSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(TutorTable::getColumns())
            ->filters(TutorTable::getFilters())
            ->actions(TutorTable::getActions())
            ->bulkActions(TutorTable::getBulkActions())
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(TutorInfolistSchema::getSchema());
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
            'index' => Pages\ListTutors::route('/'),
            'create' => Pages\CreateTutor::route('/create'),
            'view' => Pages\ViewTutor::route('/{record}'),
            'edit' => Pages\EditTutor::route('/{record}/edit'),
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
