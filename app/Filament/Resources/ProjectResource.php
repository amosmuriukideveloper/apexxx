<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\Schemas\ProjectFormSchema;
use App\Filament\Resources\ProjectResource\Schemas\ProjectInfolistSchema;
use App\Filament\Resources\ProjectResource\Tables\ProjectTable;
use App\Models\Project;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Projects';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema(ProjectFormSchema::getSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(ProjectTable::getColumns())
            ->filters(ProjectTable::getFilters())
            ->actions(ProjectTable::getActions())
            ->bulkActions(ProjectTable::getBulkActions())
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(ProjectInfolistSchema::getSchema());
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
            'submit' => Pages\SubmitProject::route('/{record}/submit'),
            'review-submission' => Pages\ReviewSubmission::route('/{record}/review-submission'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $underReview = static::getModel()::where('status', 'under_review')->count();
        $awaiting = static::getModel()::where('status', 'awaiting_assignment')->count();
        return $underReview + $awaiting;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
                        
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->rows(4)
                            ->columnSpan(2),
                        
                        Forms\Components\Select::make('subject')
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
                            ])
                            ->required()
                            ->searchable(),
                        
                        Forms\Components\Select::make('difficulty_level')
                            ->options([
                                'beginner' => 'Beginner',
                                'intermediate' => 'Intermediate',
                                'advanced' => 'Advanced',
                                'expert' => 'Expert',
                            ])
                            ->required(),
                        
                        Forms\Components\DateTimePicker::make('deadline')
                            ->required()
                            ->minDate(now())
                            ->disabled(fn () => !$user->can('edit_projects')),
                        
                        Forms\Components\TextInput::make('budget')
                            ->numeric()
                            ->prefix('$')
                            ->visible(fn () => $user->isStudent() || $user->isAnyAdmin()),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Assignment & Status')
                    ->schema([
                        Forms\Components\Select::make('assigned_expert_id')
                            ->label('Assigned Expert')
                            ->relationship('assignedExpert', 'name')
                            ->options(User::role('expert')->pluck('name', 'id'))
                            ->searchable()
                            ->visible(fn () => $user->can('assign_projects')),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'assigned' => 'Assigned',
                                'in_progress' => 'In Progress',
                                'review' => 'Under Review',
                                'revision_requested' => 'Revision Requested',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->disabled(fn () => $user->isStudent()),
                        
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->visible(fn () => $user->isAnyAdmin())
                            ->rows(3),
                    ])
                    ->visible(fn () => !$user->isStudent())
                    ->columns(2),

                Forms\Components\Section::make('Files & Deliverables')
                    ->schema([
                        Forms\Components\FileUpload::make('attachments')
                            ->multiple()
                            ->directory('project-attachments')
                            ->visible(fn () => $user->can('upload_deliverables')),
                        
                        Forms\Components\FileUpload::make('deliverables')
                            ->multiple()
                            ->directory('project-deliverables')
                            ->visible(fn () => $user->isExpert() || $user->isAnyAdmin()),
                    ])
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
                    return $query->where('student_id', $user->id);
                } elseif ($user->isExpert()) {
                    return $query->where('assigned_expert_id', $user->id);
                }
                // Admins see all projects
                return $query;
            })
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student')
                    ->visible(fn () => !$user->isStudent())
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('assignedExpert.name')
                    ->label('Expert')
                    ->visible(fn () => $user->isStudent() || $user->isAnyAdmin())
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending',
                        'warning' => 'assigned',
                        'primary' => 'in_progress',
                        'info' => 'review',
                        'danger' => 'revision_requested',
                        'success' => 'completed',
                        'gray' => 'cancelled',
                    ]),
                
                Tables\Columns\BadgeColumn::make('subject')
                    ->visible(fn () => $user->isExpert() || $user->isAnyAdmin()),
                
                Tables\Columns\BadgeColumn::make('difficulty_level')
                    ->label('Difficulty')
                    ->colors([
                        'success' => 'beginner',
                        'warning' => 'intermediate',
                        'danger' => 'advanced',
                        'gray' => 'expert',
                    ]),
                
                Tables\Columns\TextColumn::make('budget')
                    ->money('USD')
                    ->visible(fn () => $user->isStudent() || $user->isAnyAdmin()),
                
                Tables\Columns\TextColumn::make('deadline')
                    ->dateTime()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'assigned' => 'Assigned',
                        'in_progress' => 'In Progress',
                        'review' => 'Under Review',
                        'revision_requested' => 'Revision Requested',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
                
                Tables\Filters\SelectFilter::make('subject')
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
                    ])
                    ->visible(fn () => $user->isExpert() || $user->isAnyAdmin()),
                
                Tables\Filters\Filter::make('my_projects')
                    ->label('My Projects Only')
                    ->query(fn (Builder $query) => $query->where('student_id', $user->id))
                    ->visible(fn () => $user->isStudent()),
                
                Tables\Filters\Filter::make('overdue')
                    ->query(fn (Builder $query) => $query->where('deadline', '<', now()))
                    ->visible(fn () => $user->isAnyAdmin()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                
                Tables\Actions\EditAction::make()
                    ->visible(fn () => Auth::user()->can('edit_projects')),
                
                Tables\Actions\Action::make('assign')
                    ->label('Assign Expert')
                    ->icon('heroicon-o-user-plus')
                    ->color('warning')
                    ->visible(fn () => Auth::user()->can('assign_projects'))
                    ->form([
                        Forms\Components\Select::make('expert_id')
                            ->label('Select Expert')
                            ->options(User::role('expert')->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                    ])
                    ->action(function (array $data, $record) {
                        $record->update([
                            'assigned_expert_id' => $data['expert_id'],
                            'status' => 'assigned',
                        ]);
                    }),
                
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => Auth::user()->can('approve_projects') && $record->status === 'review')
                    ->action(fn ($record) => $record->update(['status' => 'completed'])),
                
                Tables\Actions\Action::make('request_revision')
                    ->label('Request Revision')
                    ->icon('heroicon-o-arrow-path')
                    ->color('danger')
                    ->visible(fn ($record) => Auth::user()->can('request_revisions') && in_array($record->status, ['review', 'completed']))
                    ->form([
                        Forms\Components\Textarea::make('revision_notes')
                            ->label('Revision Notes')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (array $data, $record) {
                        $record->update([
                            'status' => 'revision_requested',
                            'revision_notes' => $data['revision_notes'],
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()->can('delete_projects')),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
