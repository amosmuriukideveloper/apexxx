<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\ProjectSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\DB;

class ReviewSubmission extends Page
{
    protected static string $resource = ProjectResource::class;

    protected static string $view = 'filament.resources.project-resource.pages.review-submission';

    public ?array $data = [];

    protected static ?string $title = 'Review Project Submission';

    public ?ProjectSubmission $submission = null;

    public function mount(): void
    {
        // Get the latest submission for this project
        $this->submission = ProjectSubmission::where('project_id', $this->record->id)
            ->where('admin_review_status', 'pending')
            ->latest()
            ->first();

        if (!$this->submission) {
            Notification::make()
                ->warning()
                ->title('No Pending Submission')
                ->body('There is no pending submission to review for this project.')
                ->send();
            
            $this->redirect(ProjectResource::getUrl('view', ['record' => $this->record]));
        }

        $this->form->fill([
            'decision' => null,
            'quality_score' => null,
            'admin_notes' => null,
            'revision_notes' => null,
        ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Infolists\Components\Section::make('Project Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('project_number')
                            ->label('Project #')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('title')
                            ->label('Title'),
                        Infolists\Components\TextEntry::make('expert.name')
                            ->label('Expert'),
                        Infolists\Components\BadgeEntry::make('status')
                            ->colors([
                                'warning' => 'under_review',
                                'info' => 'in_progress',
                                'primary' => 'revision_required',
                            ]),
                    ])->columns(4),

                Infolists\Components\Section::make('Submission Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('submission.version_number')
                            ->label('Version')
                            ->formatStateUsing(fn ($state) => 'Version ' . $state),
                        Infolists\Components\TextEntry::make('submission.submission_type')
                            ->label('Type')
                            ->badge()
                            ->colors([
                                'success' => 'initial',
                                'warning' => 'revision',
                            ]),
                        Infolists\Components\TextEntry::make('submission.created_at')
                            ->label('Submitted On')
                            ->dateTime('M d, Y H:i'),
                        Infolists\Components\TextEntry::make('submission.description')
                            ->label('Submission Notes')
                            ->placeholder('No notes provided')
                            ->columnSpanFull(),
                    ])->columns(3)
                    ->visible(fn () => $this->submission !== null),

                Infolists\Components\Section::make('Quality Scores')
                    ->schema([
                        Infolists\Components\TextEntry::make('submission.turnitin_score')
                            ->label('Turnitin Similarity')
                            ->suffix('%')
                            ->color(fn ($state) => match(true) {
                                $state <= 15 => 'success',
                                $state <= 25 => 'warning',
                                default => 'danger',
                            })
                            ->weight('bold')
                            ->size('lg'),
                        Infolists\Components\TextEntry::make('submission.ai_detection_score')
                            ->label('AI Content Detection')
                            ->suffix('%')
                            ->color(fn ($state) => match(true) {
                                $state <= 20 => 'success',
                                $state <= 35 => 'warning',
                                default => 'danger',
                            })
                            ->weight('bold')
                            ->size('lg'),
                    ])->columns(2)
                    ->visible(fn () => $this->submission !== null),

                Infolists\Components\Section::make('Documents')
                    ->schema([
                        Infolists\Components\Actions::make([
                            Infolists\Components\Actions\Action::make('view_turnitin')
                                ->label('View Turnitin Report')
                                ->icon('heroicon-o-document-text')
                                ->color('primary')
                                ->url(fn () => asset('storage/' . $this->submission->turnitin_report_path))
                                ->openUrlInNewTab()
                                ->visible(fn () => $this->submission && $this->submission->turnitin_report_path),
                            Infolists\Components\Actions\Action::make('view_ai_report')
                                ->label('View AI Detection Report')
                                ->icon('heroicon-o-document-chart-bar')
                                ->color('info')
                                ->url(fn () => asset('storage/' . $this->submission->ai_detection_report_path))
                                ->openUrlInNewTab()
                                ->visible(fn () => $this->submission && $this->submission->ai_detection_report_path),
                            Infolists\Components\Actions\Action::make('view_deliverables')
                                ->label('View All Deliverables')
                                ->icon('heroicon-o-folder-open')
                                ->color('success')
                                ->url(fn () => ProjectResource::getUrl('view', ['record' => $this->record]))
                                ->openUrlInNewTab(),
                        ]),
                    ])
                    ->visible(fn () => $this->submission !== null),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Quality Checklist')
                    ->description('Verify all quality requirements before making a decision')
                    ->schema([
                        Forms\Components\Checkbox::make('check_originality')
                            ->label('✓ Originality verified (plagiarism check passed)')
                            ->helperText('Turnitin score within acceptable limits'),
                        Forms\Components\Checkbox::make('check_ai_content')
                            ->label('✓ AI content within acceptable limits')
                            ->helperText('AI detection score reviewed and acceptable'),
                        Forms\Components\Checkbox::make('check_requirements')
                            ->label('✓ All project requirements met')
                            ->helperText('Deliverables match project specifications'),
                        Forms\Components\Checkbox::make('check_formatting')
                            ->label('✓ Formatting and presentation standards met')
                            ->helperText('Professional quality and proper structure'),
                        Forms\Components\Checkbox::make('check_technical')
                            ->label('✓ Technical accuracy verified')
                            ->helperText('Content is technically correct and accurate'),
                        Forms\Components\Checkbox::make('check_deadline')
                            ->label('✓ Deadline compliance confirmed')
                            ->helperText('Submitted within agreed timeline'),
                    ])->columns(1)
                    ->collapsible(),

                Forms\Components\Section::make('Review Decision')
                    ->schema([
                        Forms\Components\Radio::make('decision')
                            ->label('Decision')
                            ->options([
                                'approve' => 'Approve Submission',
                                'revision' => 'Request Revision',
                                'reject' => 'Reject Submission',
                            ])
                            ->required()
                            ->live()
                            ->descriptions([
                                'approve' => 'Mark project as completed and release payment',
                                'revision' => 'Request changes from expert',
                                'reject' => 'Reject work and potentially reassign',
                            ]),
                        
                        Forms\Components\TextInput::make('quality_score')
                            ->label('Quality Score (0-100)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->visible(fn ($get) => $get('decision') === 'approve')
                            ->helperText('This score will be recorded for the expert\'s profile'),
                        
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes (Internal)')
                            ->rows(3)
                            ->placeholder('Add internal notes about this review...')
                            ->helperText('These notes are for internal use only'),
                        
                        Forms\Components\Textarea::make('revision_notes')
                            ->label('Revision Requirements')
                            ->rows(4)
                            ->required(fn ($get) => $get('decision') === 'revision')
                            ->visible(fn ($get) => $get('decision') === 'revision')
                            ->placeholder('Specify what needs to be revised...')
                            ->helperText('These instructions will be sent to the expert'),
                        
                        Forms\Components\DateTimePicker::make('revision_deadline')
                            ->label('Revision Deadline')
                            ->native(false)
                            ->minDate(now())
                            ->visible(fn ($get) => $get('decision') === 'revision')
                            ->helperText('Optional: Set a deadline for the revision'),
                        
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->rows(4)
                            ->required(fn ($get) => $get('decision') === 'reject')
                            ->visible(fn ($get) => $get('decision') === 'reject')
                            ->placeholder('Explain why this submission is being rejected...')
                            ->helperText('This will be communicated to the student and expert'),
                        
                        Forms\Components\Toggle::make('reassign_project')
                            ->label('Reassign to Different Expert')
                            ->visible(fn ($get) => $get('decision') === 'reject')
                            ->helperText('Make this project available for another expert'),
                    ]),
            ])
            ->statePath('data');
    }

    public function approve(): void
    {
        try {
            DB::beginTransaction();

            $data = $this->form->getState();

            // Update submission
            $this->submission->update([
                'admin_review_status' => 'approved',
                'admin_notes' => $data['admin_notes'] ?? null,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            // Update project
            $this->record->update([
                'status' => 'completed',
                'quality_score' => $data['quality_score'] ?? null,
                'admin_notes' => $data['admin_notes'] ?? null,
                'completed_at' => now(),
                'payment_status' => 'confirmed', // Mark earnings as confirmed
            ]);

            // Update expert's stats (if expert model exists)
            if ($this->record->expert_id) {
                $expert = \App\Models\Expert::find($this->record->expert_id);
                if ($expert) {
                    $expert->increment('total_projects_completed');
                    $expert->increment('total_earnings', $this->record->expert_earnings ?? 0);
                    
                    // Update average rating if quality score provided
                    if (!empty($data['quality_score'])) {
                        $currentRating = $expert->rating ?? 0;
                        $totalProjects = $expert->total_projects_completed;
                        $newRating = (($currentRating * ($totalProjects - 1)) + $data['quality_score']) / $totalProjects;
                        $expert->update(['rating' => round($newRating, 2)]);
                    }
                }
            }

            DB::commit();

            // TODO: Send notifications
            // - Notify expert of approval
            // - Notify student that work is ready
            // - Create transaction record

            Notification::make()
                ->success()
                ->title('Submission Approved')
                ->body('Project marked as completed. Notifications sent to expert and student.')
                ->persistent()
                ->send();

            $this->redirect(ProjectResource::getUrl('view', ['record' => $this->record]));

        } catch (\Exception $e) {
            DB::rollBack();
            
            Notification::make()
                ->danger()
                ->title('Approval Failed')
                ->body('Error: ' . $e->getMessage())
                ->send();
        }
    }

    public function requestRevision(): void
    {
        $data = $this->form->getState();

        if (empty($data['revision_notes'])) {
            Notification::make()
                ->warning()
                ->title('Revision Notes Required')
                ->body('Please provide detailed revision requirements.')
                ->send();
            return;
        }

        try {
            DB::beginTransaction();

            // Update submission
            $this->submission->update([
                'admin_review_status' => 'revision_requested',
                'admin_notes' => $data['admin_notes'] ?? null,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            // Update project
            $updateData = [
                'status' => 'revision_required',
                'revision_notes' => $data['revision_notes'],
                'admin_notes' => $data['admin_notes'] ?? null,
            ];

            if (!empty($data['revision_deadline'])) {
                $updateData['deadline'] = $data['revision_deadline'];
            }

            $this->record->update($updateData);

            DB::commit();

            // TODO: Send notification to expert with revision requirements

            Notification::make()
                ->info()
                ->title('Revision Requested')
                ->body('Expert will be notified of the required revisions.')
                ->send();

            $this->redirect(ProjectResource::getUrl('view', ['record' => $this->record]));

        } catch (\Exception $e) {
            DB::rollBack();
            
            Notification::make()
                ->danger()
                ->title('Request Failed')
                ->body('Error: ' . $e->getMessage())
                ->send();
        }
    }

    public function reject(): void
    {
        $data = $this->form->getState();

        if (empty($data['rejection_reason'])) {
            Notification::make()
                ->warning()
                ->title('Rejection Reason Required')
                ->body('Please provide a detailed reason for rejection.')
                ->send();
            return;
        }

        try {
            DB::beginTransaction();

            // Update submission
            $this->submission->update([
                'admin_review_status' => 'rejected',
                'admin_notes' => $data['rejection_reason'],
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            // Update project based on reassignment decision
            if ($data['reassign_project'] ?? false) {
                $this->record->update([
                    'status' => 'awaiting_assignment',
                    'expert_id' => null,
                    'assigned_expert_id' => null,
                    'admin_notes' => $data['rejection_reason'],
                ]);
            } else {
                $this->record->update([
                    'status' => 'cancelled',
                    'admin_notes' => $data['rejection_reason'],
                ]);
            }

            DB::commit();

            // TODO: Send notifications
            // - Notify expert of rejection
            // - Notify student of situation
            // - Handle refund if applicable

            Notification::make()
                ->warning()
                ->title('Submission Rejected')
                ->body('Notifications sent to relevant parties.')
                ->send();

            $this->redirect(ProjectResource::getUrl('view', ['record' => $this->record]));

        } catch (\Exception $e) {
            DB::rollBack();
            
            Notification::make()
                ->danger()
                ->title('Rejection Failed')
                ->body('Error: ' . $e->getMessage())
                ->send();
        }
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        match($data['decision']) {
            'approve' => $this->approve(),
            'revision' => $this->requestRevision(),
            'reject' => $this->reject(),
            default => null,
        };
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('submit')
                ->label('Submit Review')
                ->submit('submit')
                ->color('primary'),
        ];
    }
}
