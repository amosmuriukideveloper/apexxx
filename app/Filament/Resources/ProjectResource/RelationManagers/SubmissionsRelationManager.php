<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';
    protected static ?string $title = 'Submissions & Revisions';
    protected static ?string $icon = 'heroicon-o-document-arrow-up';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Submission Details')
                    ->schema([
                        Forms\Components\Select::make('submission_type')
                            ->options([
                                'initial' => 'Initial Submission',
                                'revision' => 'Revision',
                                'final' => 'Final Submission',
                            ])
                            ->required()
                            ->default('initial'),
                        
                        Forms\Components\TextInput::make('version_number')
                            ->label('Version')
                            ->numeric()
                            ->default(1)
                            ->disabled(),
                        
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->placeholder('Describe the changes made in this submission...'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Files')
                    ->schema([
                        Forms\Components\FileUpload::make('deliverable_files')
                            ->label('Deliverable Files')
                            ->multiple()
                            ->directory('project-deliverables')
                            ->downloadable()
                            ->required()
                            ->helperText('Upload your work files (docs, PDFs, etc.)'),
                        
                        Forms\Components\FileUpload::make('turnitin_report_path')
                            ->label('Turnitin Report')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('turnitin-reports')
                            ->downloadable()
                            ->helperText('Upload plagiarism report (required)'),
                        
                        Forms\Components\FileUpload::make('ai_detection_report_path')
                            ->label('AI Detection Report')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('ai-reports')
                            ->downloadable()
                            ->helperText('Upload AI detection report (GPTZero, Originality.ai, etc.)'),
                    ]),

                Forms\Components\Section::make('Quality Scores')
                    ->schema([
                        Forms\Components\TextInput::make('turnitin_score')
                            ->label('Turnitin Score (%)')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100),
                        
                        Forms\Components\TextInput::make('ai_detection_score')
                            ->label('AI Detection Score (%)')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        $user = Auth::user();
        
        return $table
            ->recordTitleAttribute('submission_type')
            ->columns([
                Tables\Columns\TextColumn::make('version_number')
                    ->label('Version')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('submission_type')
                    ->badge()
                    ->colors([
                        'info' => 'initial',
                        'warning' => 'revision',
                        'success' => 'final',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                
                Tables\Columns\TextColumn::make('expert.name')
                    ->label('Submitted By')
                    ->visible(fn () => !$user->hasRole('expert')),
                
                Tables\Columns\BadgeColumn::make('admin_review_status')
                    ->label('Review Status')
                    ->colors([
                        'secondary' => 'pending',
                        'success' => 'approved',
                        'warning' => 'revision_required',
                        'danger' => 'rejected',
                    ]),
                
                Tables\Columns\TextColumn::make('turnitin_score')
                    ->label('Turnitin')
                    ->formatStateUsing(fn ($state) => $state ? $state . '%' : 'N/A')
                    ->color(fn ($state) => $state > 20 ? 'danger' : 'success'),
                
                Tables\Columns\TextColumn::make('ai_detection_score')
                    ->label('AI Detection')
                    ->formatStateUsing(fn ($state) => $state ? $state . '%' : 'N/A')
                    ->color(fn ($state) => $state > 30 ? 'danger' : 'success'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('submission_type')
                    ->options([
                        'initial' => 'Initial',
                        'revision' => 'Revision',
                        'final' => 'Final',
                    ]),
                
                Tables\Filters\SelectFilter::make('admin_review_status')
                    ->options([
                        'pending' => 'Pending Review',
                        'approved' => 'Approved',
                        'revision_required' => 'Needs Revision',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('New Submission')
                    ->visible(fn () => $user->hasRole('expert') || $user->hasRole('super_admin'))
                    ->mutateFormDataUsing(function (array $data) use ($user) {
                        $data['expert_id'] = $user->id;
                        $data['version_number'] = $this->ownerRecord->submissions()->max('version_number') + 1;
                        return $data;
                    })
                    ->after(function ($record) {
                        // Update project status to under review
                        $this->ownerRecord->update([
                            'status' => 'under_review',
                            'submitted_at' => now(),
                        ]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Submission Uploaded')
                            ->success()
                            ->body('Your submission has been sent for admin review.')
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn ($record) => "Submission Version {$record->version_number}"),
                
                Tables\Actions\Action::make('review')
                    ->label('Review')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('warning')
                    ->visible(fn ($record) => $user->hasRole(['super_admin', 'admin']) && $record->admin_review_status === 'pending')
                    ->url(fn ($record) => route('filament.platform.resources.projects.review-submission', [
                        'record' => $this->ownerRecord
                    ])),
                
                Tables\Actions\Action::make('download_all')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->action(function ($record) {
                        // Implement download logic
                        \Filament\Notifications\Notification::make()
                            ->title('Download Started')
                            ->info()
                            ->send();
                    }),
                
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => $user->hasRole(['super_admin', 'admin'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => $user->hasRole(['super_admin', 'admin'])),
                ]),
            ])
            ->defaultSort('version_number', 'desc')
            ->emptyStateHeading('No submissions yet')
            ->emptyStateDescription('The expert hasn\'t submitted any work yet.')
            ->emptyStateIcon('heroicon-o-document-arrow-up');
    }
}
