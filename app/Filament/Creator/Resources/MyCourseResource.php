<?php

namespace App\Filament\Creator\Resources;

use App\Filament\Creator\Resources\MyCourseResource\Pages;
use App\Filament\Creator\Resources\MyCourseResource\RelationManagers;
use App\Models\Course;
use App\Models\CourseCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MyCourseResource extends Resource
{
    protected static ?string $model = Course::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'My Courses';
    protected static ?string $navigationGroup = 'My Content';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Course';
    protected static ?string $pluralModelLabel = 'My Courses';

    public static function getNavigationBadge(): ?string
    {
        return (string) Course::where('creator_id', Auth::id())->count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('creator_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Basic Information')
                        ->schema([
                            Forms\Components\Section::make('Course Details')
                                ->schema([
                                    Forms\Components\TextInput::make('title')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn ($state, Forms\Set $set) => 
                                            $set('slug', Str::slug($state) . '-' . Str::random(6))
                                        )
                                        ->columnSpan(2),
                                    
                                    Forms\Components\Hidden::make('slug'),
                                    
                                    Forms\Components\Textarea::make('short_description')
                                        ->label('Short Description')
                                        ->rows(2)
                                        ->maxLength(500)
                                        ->helperText('Brief overview (max 500 characters)')
                                        ->columnSpan(2),
                                    
                                    Forms\Components\RichEditor::make('description')
                                        ->label('Full Description')
                                        ->required()
                                        ->columnSpan(2)
                                        ->fileAttachmentsDirectory('course-attachments'),
                                    
                                    Forms\Components\Select::make('category_id')
                                        ->label('Category')
                                        ->options(CourseCategory::pluck('name', 'id'))
                                        ->required()
                                        ->searchable()
                                        ->preload(),
                                    
                                    Forms\Components\Select::make('difficulty')
                                        ->options([
                                            'beginner' => 'Beginner',
                                            'intermediate' => 'Intermediate',
                                            'advanced' => 'Advanced',
                                        ])
                                        ->required(),
                                    
                                    Forms\Components\Select::make('language')
                                        ->options([
                                            'en' => 'English',
                                            'es' => 'Spanish',
                                            'fr' => 'French',
                                            'de' => 'German',
                                            'zh' => 'Chinese',
                                            'ja' => 'Japanese',
                                            'ar' => 'Arabic',
                                        ])
                                        ->default('en')
                                        ->required(),
                                    
                                    Forms\Components\Toggle::make('certificate_available')
                                        ->label('Offer Certificate')
                                        ->helperText('Students will receive a certificate upon completion'),
                                ])
                                ->columns(2),
                        ]),
                    
                    Forms\Components\Wizard\Step::make('Pricing & Media')
                        ->schema([
                            Forms\Components\Section::make('Pricing')
                                ->schema([
                                    Forms\Components\TextInput::make('price')
                                        ->label('Regular Price')
                                        ->numeric()
                                        ->prefix('$')
                                        ->required()
                                        ->minValue(0)
                                        ->helperText('Set to 0 for free courses'),
                                    
                                    Forms\Components\TextInput::make('sale_price')
                                        ->label('Sale Price (Optional)')
                                        ->numeric()
                                        ->prefix('$')
                                        ->minValue(0)
                                        ->lt('price')
                                        ->helperText('Leave empty if no discount'),
                                ])
                                ->columns(2),
                            
                            Forms\Components\Section::make('Media')
                                ->schema([
                                    Forms\Components\FileUpload::make('thumbnail')
                                        ->label('Course Thumbnail')
                                        ->image()
                                        ->disk('public')
                                        ->directory('course-thumbnails')
                                        ->imageEditor()
                                        ->imageCropAspectRatio('16:9')
                                        ->helperText('Recommended: 1280x720px')
                                        ->columnSpan(2),
                                    
                                    Forms\Components\FileUpload::make('intro_video')
                                        ->label('Intro/Preview Video')
                                        ->disk('public')
                                        ->directory('course-previews')
                                        ->acceptedFileTypes(['video/mp4', 'video/webm'])
                                        ->maxSize(102400) // 100MB
                                        ->helperText('Optional promotional video')
                                        ->columnSpan(2),
                                ])
                                ->columns(2),
                        ]),
                    
                    Forms\Components\Wizard\Step::make('Additional Information')
                        ->schema([
                            Forms\Components\Section::make('Course Learning')
                                ->schema([
                                    Forms\Components\TagsInput::make('objectives')
                                        ->label('Learning Objectives')
                                        ->placeholder('What will students learn?')
                                        ->helperText('Press Enter to add each objective')
                                        ->columnSpan(2),
                                    
                                    Forms\Components\TagsInput::make('requirements')
                                        ->label('Requirements')
                                        ->placeholder('What students need before taking this course')
                                        ->helperText('Prerequisites, tools, or knowledge needed')
                                        ->columnSpan(2),
                                    
                                    Forms\Components\TagsInput::make('target_audience')
                                        ->label('Target Audience')
                                        ->placeholder('Who is this course for?')
                                        ->helperText('Define your ideal students')
                                        ->columnSpan(2),
                                ])
                                ->columns(2),
                        ]),
                ])
                ->columnSpan('full')
                ->skippable(),
                
                Forms\Components\Hidden::make('creator_id')
                    ->default(Auth::id()),
                
                Forms\Components\Hidden::make('status')
                    ->default(Course::STATUS_DRAFT),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->size(80)
                    ->defaultImageUrl(url('/images/course-placeholder.png')),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->short_description),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => Course::STATUS_DRAFT,
                        'warning' => Course::STATUS_PENDING_REVIEW,
                        'success' => Course::STATUS_APPROVED,
                        'primary' => Course::STATUS_PUBLISHED,
                        'danger' => Course::STATUS_REJECTED,
                    ])
                    ->icons([
                        'heroicon-o-pencil' => Course::STATUS_DRAFT,
                        'heroicon-o-clock' => Course::STATUS_PENDING_REVIEW,
                        'heroicon-o-check-circle' => Course::STATUS_APPROVED,
                        'heroicon-o-globe-alt' => Course::STATUS_PUBLISHED,
                        'heroicon-o-x-circle' => Course::STATUS_REJECTED,
                    ]),
                
                Tables\Columns\TextColumn::make('enrollments_count')
                    ->label('Students')
                    ->counts('enrollments')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('average_rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1) . ' ⭐' : 'No ratings'),
                
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('revenue')
                    ->label('Revenue')
                    ->money('USD')
                    ->getStateUsing(function ($record) {
                        return $record->enrollments()
                            ->where('status', '!=', 'refunded')
                            ->sum('amount_paid');
                    }),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        Course::STATUS_DRAFT => 'Draft',
                        Course::STATUS_PENDING_REVIEW => 'Pending Review',
                        Course::STATUS_APPROVED => 'Approved',
                        Course::STATUS_PUBLISHED => 'Published',
                        Course::STATUS_REJECTED => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    
                    Tables\Actions\Action::make('build')
                        ->label('Course Builder')
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->color('primary')
                        ->url(fn ($record) => MyCourseResource::getUrl('builder', ['record' => $record])),
                    
                    Tables\Actions\Action::make('submit_review')
                        ->label('Submit for Review')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('warning')
                        ->visible(fn ($record) => $record->status === Course::STATUS_DRAFT)
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->submitForReview();
                            \Filament\Notifications\Notification::make()
                                ->title('Course Submitted')
                                ->success()
                                ->body('Your course has been submitted for review.')
                                ->send();
                        }),
                    
                    Tables\Actions\Action::make('preview')
                        ->label('Preview')
                        ->icon('heroicon-o-eye')
                        ->url(fn ($record) => route('course.preview', $record->slug))
                        ->openUrlInNewTab(),
                    
                    Tables\Actions\DeleteAction::make()
                        ->visible(fn ($record) => $record->status === Course::STATUS_DRAFT),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->authorize('deleteAny'),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SectionsRelationManager::class,
            RelationManagers\LecturesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMyCourses::route('/'),
            'create' => Pages\CreateMyCourse::route('/create'),
            'view' => Pages\ViewMyCourse::route('/{record}'),
            'edit' => Pages\EditMyCourse::route('/{record}/edit'),
            'builder' => Pages\CourseBuilder::route('/{record}/builder'),
        ];
    }
}
