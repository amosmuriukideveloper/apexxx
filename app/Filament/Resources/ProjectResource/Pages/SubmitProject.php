<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Models\ProjectSubmission;
use App\Models\ProjectMaterial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class SubmitProject extends Page
{
    protected static string $resource = ProjectResource::class;

    protected static string $view = 'filament.resources.project-resource.pages.submit-project';

    public ?array $data = [];

    protected static ?string $title = 'Submit Project';

    public function mount(): void
    {
        // Check if project can be submitted
        if (!in_array($this->record->status, ['in_progress', 'revision_required'])) {
            Notification::make()
                ->warning()
                ->title('Cannot Submit')
                ->body('This project is not in a submittable state.')
                ->send();
            
            $this->redirect(ProjectResource::getUrl('view', ['record' => $this->record]));
        }

        $this->form->fill([]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Deliverables')
                        ->description('Upload your primary project files')
                        ->schema([
                            Forms\Components\FileUpload::make('deliverables')
                                ->label('Project Deliverables')
                                ->multiple()
                                ->directory('projects/deliverables')
                                ->maxSize(51200) // 50MB
                                ->acceptedFileTypes([
                                    'application/pdf',
                                    'application/msword',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                    'application/vnd.ms-powerpoint',
                                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                    'application/zip',
                                    'image/*',
                                ])
                                ->downloadable()
                                ->openable()
                                ->previewable()
                                ->required()
                                ->helperText('Upload all project files (documents, presentations, code, etc.)'),
                            Forms\Components\Textarea::make('deliverable_notes')
                                ->label('Deliverable Notes')
                                ->rows(3)
                                ->placeholder('Describe what you have included in your submission...')
                                ->helperText('Optional: Explain the structure of your deliverables'),
                        ]),

                    Forms\Components\Wizard\Step::make('Turnitin Report')
                        ->description('Upload plagiarism check report')
                        ->schema([
                            Forms\Components\FileUpload::make('turnitin_report')
                                ->label('Turnitin Plagiarism Report')
                                ->directory('projects/reports/turnitin')
                                ->acceptedFileTypes(['application/pdf'])
                                ->maxSize(10240) // 10MB
                                ->required()
                                ->downloadable()
                                ->openable()
                                ->helperText('Upload the official Turnitin plagiarism report in PDF format'),
                            Forms\Components\TextInput::make('turnitin_score')
                                ->label('Turnitin Similarity Score (%)')
                                ->numeric()
                                ->required()
                                ->minValue(0)
                                ->maxValue(100)
                                ->suffix('%')
                                ->helperText('Enter the overall similarity percentage from the report')
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state > 20) {
                                        Notification::make()
                                            ->warning()
                                            ->title('High Similarity Score')
                                            ->body('Similarity score above 20% may require review.')
                                            ->send();
                                    }
                                }),
                            Forms\Components\Textarea::make('turnitin_notes')
                                ->label('Turnitin Notes')
                                ->rows(2)
                                ->placeholder('Explain any high similarity scores or matches...')
                                ->helperText('Optional: Provide context for the similarity score'),
                        ]),

                    Forms\Components\Wizard\Step::make('AI Detection Report')
                        ->description('Upload AI content detection report')
                        ->schema([
                            Forms\Components\FileUpload::make('ai_detection_report')
                                ->label('AI Detection Report (GPTZero, Originality.ai, etc.)')
                                ->directory('projects/reports/ai-detection')
                                ->acceptedFileTypes(['application/pdf', 'image/*'])
                                ->maxSize(10240) // 10MB
                                ->required()
                                ->downloadable()
                                ->openable()
                                ->helperText('Upload AI content detection report in PDF or image format'),
                            Forms\Components\TextInput::make('ai_detection_score')
                                ->label('AI Content Detection Score (%)')
                                ->numeric()
                                ->required()
                                ->minValue(0)
                                ->maxValue(100)
                                ->suffix('%')
                                ->helperText('Enter the AI-generated content percentage')
                                ->reactive()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state > 30) {
                                        Notification::make()
                                            ->warning()
                                            ->title('High AI Detection')
                                            ->body('AI content above 30% may require review.')
                                            ->send();
                                    }
                                }),
                            Forms\Components\Textarea::make('ai_detection_notes')
                                ->label('AI Detection Notes')
                                ->rows(2)
                                ->placeholder('Explain any detected AI content...')
                                ->helperText('Optional: Provide context for AI detection results'),
                        ]),

                    Forms\Components\Wizard\Step::make('Review & Submit')
                        ->description('Final review and submission notes')
                        ->schema([
                            Forms\Components\Placeholder::make('summary')
                                ->label('Submission Summary')
                                ->content(function ($get) {
                                    $deliverables = $get('deliverables');
                                    $turnitinScore = $get('turnitin_score');
                                    $aiScore = $get('ai_detection_score');
                                    
                                    $summary = "📁 Deliverables: " . (is_array($deliverables) ? count($deliverables) : 0) . " file(s)\n";
                                    $summary .= "📊 Turnitin Score: " . ($turnitinScore ?? 'N/A') . "%\n";
                                    $summary .= "🤖 AI Detection: " . ($aiScore ?? 'N/A') . "%\n";
                                    
                                    return $summary;
                                }),
                            Forms\Components\Textarea::make('submission_notes')
                                ->label('Submission Notes')
                                ->rows(4)
                                ->placeholder('Add any final notes about this submission...')
                                ->helperText('Optional: Include any important information for the reviewer'),
                            Forms\Components\Checkbox::make('confirm_original')
                                ->label('I confirm this is my original work')
                                ->required()
                                ->accepted(),
                            Forms\Components\Checkbox::make('confirm_requirements')
                                ->label('I confirm all requirements have been met')
                                ->required()
                                ->accepted(),
                            Forms\Components\Checkbox::make('confirm_quality')
                                ->label('I confirm this work meets quality standards')
                                ->required()
                                ->accepted(),
                        ]),
                ])
                    ->submitAction(view('filament.pages.actions.wizard-submit'))
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        try {
            DB::beginTransaction();

            $data = $this->form->getState();

            // Determine version number
            $versionNumber = ProjectSubmission::where('project_id', $this->record->id)->count() + 1;
            $isRevision = $this->record->status === 'revision_required';

            // Create submission record
            $submission = ProjectSubmission::create([
                'project_id' => $this->record->id,
                'expert_id' => $this->record->expert_id,
                'submission_type' => $isRevision ? 'revision' : 'initial',
                'version_number' => $versionNumber,
                'description' => $data['submission_notes'] ?? null,
                'turnitin_report_path' => $data['turnitin_report'],
                'ai_detection_report_path' => $data['ai_detection_report'],
                'turnitin_score' => $data['turnitin_score'],
                'ai_detection_score' => $data['ai_detection_score'],
                'admin_review_status' => 'pending',
            ]);

            // Store deliverables as project materials
            if (!empty($data['deliverables'])) {
                foreach ($data['deliverables'] as $file) {
                    ProjectMaterial::create([
                        'project_id' => $this->record->id,
                        'submission_id' => $submission->id,
                        'file_path' => $file,
                        'file_type' => 'deliverable',
                        'uploaded_by' => 'expert',
                    ]);
                }
            }

            // Update project
            $this->record->update([
                'status' => 'under_review',
                'turnitin_score' => $data['turnitin_score'],
                'ai_detection_score' => $data['ai_detection_score'],
                'submitted_at' => now(),
            ]);

            DB::commit();

            // TODO: Send notification to admin
            // TODO: Send confirmation to expert

            Notification::make()
                ->success()
                ->title('Project Submitted Successfully')
                ->body('Your submission is now under review by the admin.')
                ->persistent()
                ->send();

            $this->redirect(ProjectResource::getUrl('view', ['record' => $this->record]));

        } catch (\Exception $e) {
            DB::rollBack();
            
            Notification::make()
                ->danger()
                ->title('Submission Failed')
                ->body('Error: ' . $e->getMessage())
                ->send();
        }
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('submit')
                ->label('Submit Project')
                ->submit('submit')
                ->color('success'),
        ];
    }
}
