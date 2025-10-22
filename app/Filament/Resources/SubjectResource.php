<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubjectResource\Pages;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Subject Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->maxLength(500),
                        
                        Forms\Components\Select::make('category')
                            ->options([
                                'sciences' => 'Sciences',
                                'humanities' => 'Humanities',
                                'engineering' => 'Engineering',
                                'business' => 'Business',
                                'arts' => 'Arts',
                                'mathematics' => 'Mathematics',
                                'technology' => 'Technology',
                                'health' => 'Health Sciences',
                            ])
                            ->searchable(),
                        
                        Forms\Components\TextInput::make('base_price_per_page')
                            ->label('Base Price per Page ($)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->step(0.01)
                            ->prefix('$')
                            ->helperText('Base price that will be used in project cost calculations'),
                    ])->columns(2),

                Forms\Components\Section::make('Display Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Only active subjects appear in project forms')
                            ->default(true),
                        
                        Forms\Components\TextInput::make('icon')
                            ->label('Heroicon Name')
                            ->placeholder('academic-cap')
                            ->helperText('Heroicon name without prefix (e.g., academic-cap)'),
                        
                        Forms\Components\ColorPicker::make('color')
                            ->label('Display Color'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('base_price_per_page')
                    ->label('Base Price/Page')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'sciences' => 'Sciences',
                        'humanities' => 'Humanities',
                        'engineering' => 'Engineering',
                        'business' => 'Business',
                        'arts' => 'Arts',
                        'mathematics' => 'Mathematics',
                        'technology' => 'Technology',
                        'health' => 'Health Sciences',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Subjects'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubjects::route('/'),
            'create' => Pages\CreateSubject::route('/create'),
            'edit' => Pages\EditSubject::route('/{record}/edit'),
        ];
    }
}
