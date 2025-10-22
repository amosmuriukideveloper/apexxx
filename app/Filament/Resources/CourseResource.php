<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Learning';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        
        if ($user->isStudent()) {
            return (string) $user->enrolledCourses()->count();
        } elseif ($user->isContentCreator()) {
            return (string) $user->createdCourses()->count();
        } elseif ($user->isAnyAdmin()) {
            return (string) static::getModel()::where('status', 'pending')->count();
        }
        
        return null;
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->can('view_courses');
    }

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        
        return $form
            ->schema([
                Forms\Components\Section::make('Course Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(4)
                            ->columnSpan(2),
                        
                        Forms\Components\Select::make('category')
                            ->options([
                                'mathematics' => 'Mathematics',
                                'physics' => 'Physics',
                                'chemistry' => 'Chemistry',
                                'biology' => 'Biology',
                                'computer_science' => 'Computer Science',
                                'engineering' => 'Engineering',
                                'business' => 'Business',
                                'economics' => 'Economics',
                                'literature' => 'Literature',
                                'history' => 'History',
                                'art' => 'Art & Design',
                                'music' => 'Music',
                            ])
                            ->required()
                            ->searchable(),
                        
                        Forms\Components\Select::make('level')
                            ->options([
                                'beginner' => 'Beginner',
                                'intermediate' => 'Intermediate',
                                'advanced' => 'Advanced',
                            ])
                            ->required(),
                        
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->visible(fn () => $user->isContentCreator() || $user->isAnyAdmin()),
                        
                        Forms\Components\TextInput::make('duration_hours')
                            ->label('Duration (Hours)')
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Content & Media')
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail')
                            ->image()
                            ->directory('course-thumbnails')
                            ->visible(fn () => $user->isContentCreator() || $user->isAnyAdmin()),
                        
                        Forms\Components\FileUpload::make('preview_video')
                            ->acceptedFileTypes(['video/mp4', 'video/avi', 'video/mov'])
                            ->directory('course-previews')
                            ->visible(fn () => $user->isContentCreator() || $user->isAnyAdmin()),
                        
                        Forms\Components\Repeater::make('modules')
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->rows(2),
                                Forms\Components\TextInput::make('duration_minutes')
                                    ->label('Duration (Minutes)')
                                    ->numeric(),
                            ])
                            ->visible(fn () => $user->isContentCreator() || $user->isAnyAdmin())
                            ->columns(2),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Status & Approval')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'pending' => 'Pending Approval',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'published' => 'Published',
                            ])
                            ->default('draft')
                            ->disabled(fn () => !$user->isAnyAdmin()),
                        
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->visible(fn () => $user->isAnyAdmin())
                            ->rows(3),
                    ])
                    ->visible(fn () => $user->isContentCreator() || $user->isAnyAdmin())
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();
        
        return $table
            ->query(static::getEloquentQuery())
            ->modifyQueryUsing(function (Builder $query) use ($user) {
                if ($user->isStudent()) {
                    return $query->where('status', 'published');
                } elseif ($user->isContentCreator()) {
                    return $query->where('creator_id', $user->id);
                }
                // Admins see all courses
                return $query;
            })
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->circular()
                    ->size(50),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Creator')
                    ->visible(fn () => !$user->isContentCreator())
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('category')
                    ->colors([
                        'primary' => 'mathematics',
                        'success' => 'computer_science',
                        'warning' => 'business',
                        'danger' => 'physics',
                        'info' => 'chemistry',
                        'secondary' => fn ($state) => !in_array($state, ['mathematics', 'computer_science', 'business', 'physics', 'chemistry']),
                    ]),
                
                Tables\Columns\BadgeColumn::make('level')
                    ->colors([
                        'success' => 'beginner',
                        'warning' => 'intermediate',
                        'danger' => 'advanced',
                    ]),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                        'primary' => 'published',
                    ]),
                
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->visible(fn () => $user->isContentCreator() || $user->isAnyAdmin()),
                
                Tables\Columns\TextColumn::make('enrollments_count')
                    ->label('Enrollments')
                    ->counts('enrollments')
                    ->visible(fn () => $user->isContentCreator() || $user->isAnyAdmin()),
                
                Tables\Columns\TextColumn::make('duration_hours')
                    ->label('Duration')
                    ->suffix(' hrs'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending Approval',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'published' => 'Published',
                    ])
                    ->visible(fn () => $user->isContentCreator() || $user->isAnyAdmin()),
                
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'mathematics' => 'Mathematics',
                        'physics' => 'Physics',
                        'chemistry' => 'Chemistry',
                        'biology' => 'Biology',
                        'computer_science' => 'Computer Science',
                        'engineering' => 'Engineering',
                        'business' => 'Business',
                        'economics' => 'Economics',
                        'literature' => 'Literature',
                        'history' => 'History',
                        'art' => 'Art & Design',
                        'music' => 'Music',
                    ]),
                
                Tables\Filters\SelectFilter::make('level')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                    ]),
                
                Tables\Filters\Filter::make('my_courses')
                    ->label('My Courses Only')
                    ->query(fn (Builder $query) => $query->where('creator_id', $user->id))
                    ->visible(fn () => $user->isContentCreator()),
                
                Tables\Filters\Filter::make('enrolled')
                    ->label('My Enrolled Courses')
                    ->query(fn (Builder $query) => $query->whereHas('enrollments', fn ($q) => $q->where('user_id', $user->id)))
                    ->visible(fn () => $user->isStudent()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                
                Tables\Actions\EditAction::make()
                    ->visible(fn () => Auth::user()->can('edit_courses')),
                
                Tables\Actions\Action::make('enroll')
                    ->label('Enroll')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->visible(fn ($record) => Auth::user()->isStudent() && $record->status === 'published')
                    ->action(function ($record) {
                        // Enrollment logic here
                        $record->enrollments()->create(['user_id' => Auth::id()]);
                    }),
                
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => Auth::user()->can('approve_courses') && $record->status === 'pending')
                    ->action(fn ($record) => $record->update(['status' => 'approved'])),
                
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => Auth::user()->can('reject_courses') && $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (array $data, $record) {
                        $record->update([
                            'status' => 'rejected',
                            'admin_notes' => $data['rejection_reason'],
                        ]);
                    }),
                
                Tables\Actions\Action::make('publish')
                    ->label('Publish')
                    ->icon('heroicon-o-globe-alt')
                    ->color('primary')
                    ->visible(fn ($record) => Auth::user()->can('approve_courses') && $record->status === 'approved')
                    ->action(fn ($record) => $record->update(['status' => 'published'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()->can('delete_courses')),
                ]),
            ]);
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'view' => Pages\ViewCourse::route('/{record}'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
