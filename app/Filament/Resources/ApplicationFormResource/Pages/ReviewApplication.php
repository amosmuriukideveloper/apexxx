<?php

namespace App\Filament\Resources\ApplicationFormResource\Pages;

use App\Filament\Resources\ApplicationFormResource;
use App\Models\Expert;
use App\Models\Tutor;
use App\Models\ContentCreator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReviewApplication extends Page
{
    protected static string $resource = ApplicationFormResource::class;

    protected static string $view = 'filament.resources.application-form-resource.pages.review-application';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'status' => $this->record->status,
            'review_notes' => $this->record->review_notes,
        ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Infolists\Components\Section::make('Personal Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('full_name')
                            ->label('Full Name'),
                        Infolists\Components\TextEntry::make('email')
                            ->label('Email')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('phone')
                            ->label('Phone')
                            ->copyable(),
                        Infolists\Components\BadgeEntry::make('applicant_type')
                            ->label('Applying As')
                            ->colors([
                                'primary' => 'expert',
                                'success' => 'tutor',
                                'warning' => 'content_creator',
                            ]),
                    ])->columns(2),

                Infolists\Components\Section::make('Professional Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('education_level')
                            ->label('Education Level'),
                        Infolists\Components\TextEntry::make('institution')
                            ->label('Institution'),
                        Infolists\Components\TextEntry::make('field_of_study')
                            ->label('Field of Study'),
                        Infolists\Components\TextEntry::make('years_of_experience')
                            ->label('Years of Experience')
                            ->suffix(' years'),
                        Infolists\Components\TextEntry::make('expertise_areas')
                            ->label('Expertise Areas')
                            ->badge()
                            ->separator(',')
                            ->columnSpanFull(),
                    ])->columns(2),

                Infolists\Components\Section::make('Online Presence')
                    ->schema([
                        Infolists\Components\TextEntry::make('linkedin_profile')
                            ->label('LinkedIn Profile')
                            ->url(fn ($record) => $record->linkedin_profile)
                            ->openUrlInNewTab()
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('sample_work_url')
                            ->label('Sample Work / Portfolio')
                            ->url(fn ($record) => $record->sample_work_url)
                            ->openUrlInNewTab()
                            ->placeholder('Not provided'),
                    ])->columns(2),

                Infolists\Components\Section::make('Statement of Purpose')
                    ->schema([
                        Infolists\Components\TextEntry::make('why_join')
                            ->label('Why They Want to Join')
                            ->html()
                            ->columnSpanFull(),
                    ]),

                Infolists\Components\Section::make('Application Status')
                    ->schema([
                        Infolists\Components\BadgeEntry::make('status')
                            ->colors([
                                'warning' => 'pending',
                                'info' => 'under_review',
                                'success' => 'approved',
                                'danger' => 'rejected',
                            ]),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Submitted On')
                            ->dateTime('M d, Y H:i'),
                        Infolists\Components\TextEntry::make('reviewer.name')
                            ->label('Reviewed By')
                            ->placeholder('Not reviewed'),
                        Infolists\Components\TextEntry::make('reviewed_at')
                            ->label('Reviewed On')
                            ->dateTime('M d, Y H:i')
                            ->placeholder('Not reviewed'),
                    ])->columns(2),

                Infolists\Components\Section::make('Review Notes')
                    ->schema([
                        Infolists\Components\TextEntry::make('review_notes')
                            ->placeholder('No notes yet')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => !empty($record->review_notes)),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Review Checklist')
                    ->description('Verify all requirements before making a decision')
                    ->schema([
                        Forms\Components\Checkbox::make('checklist_education')
                            ->label('Educational qualifications verified')
                            ->helperText('Check certificates and degrees'),
                        Forms\Components\Checkbox::make('checklist_experience')
                            ->label('Professional experience validated')
                            ->helperText('Verify years of experience claimed'),
                        Forms\Components\Checkbox::make('checklist_documents')
                            ->label('All required documents uploaded')
                            ->helperText('CV, certificates, ID verification'),
                        Forms\Components\Checkbox::make('checklist_portfolio')
                            ->label('Sample work/portfolio reviewed')
                            ->helperText('Quality and relevance assessed'),
                        Forms\Components\Checkbox::make('checklist_statement')
                            ->label('Statement of purpose reviewed')
                            ->helperText('Clear motivation and alignment'),
                    ])->columns(1)
                    ->collapsible(),

                Forms\Components\Section::make('Review Decision')
                    ->schema([
                        Forms\Components\Radio::make('status')
                            ->options([
                                'approved' => 'Approve Application',
                                'rejected' => 'Reject Application',
                                'under_review' => 'Keep Under Review',
                            ])
                            ->required()
                            ->live()
                            ->descriptions([
                                'approved' => 'Create account and send welcome email',
                                'rejected' => 'Send rejection email with feedback',
                                'under_review' => 'Mark for further review',
                            ]),
                        Forms\Components\Textarea::make('review_notes')
                            ->label(fn ($get) => $get('status') === 'rejected' ? 'Rejection Reason (Required)' : 'Internal Notes (Optional)')
                            ->required(fn ($get) => $get('status') === 'rejected')
                            ->rows(5)
                            ->placeholder(fn ($get) => $get('status') === 'rejected' 
                                ? 'Explain why this application is being rejected and what improvements are needed...'
                                : 'Add any internal notes about this application...')
                            ->helperText(fn ($get) => $get('status') === 'rejected'
                                ? 'This will be sent to the applicant'
                                : 'This is for internal use only'),
                    ]),
            ])
            ->statePath('data');
    }

    public function approve(): void
    {
        try {
            DB::beginTransaction();

            $data = $this->form->getState();
            $password = Str::random(12);

            // Create user account
            $userData = $this->createUserAccount($password);

            // Update application
            $this->record->update([
                'status' => 'approved',
                'review_notes' => $data['review_notes'] ?? null,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'applicant_id' => $userData['id'],
                'applicant_type' => $userData['type'],
            ]);

            DB::commit();

            // TODO: Send welcome email
            // Mail::to($this->record->email)->send(new WelcomeEmail($password));

            Notification::make()
                ->success()
                ->title('Application Approved')
                ->body("Account created! Email: {$this->record->email}, Password: {$password}")
                ->persistent()
                ->send();

            $this->redirect(ApplicationFormResource::getUrl('index'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            Notification::make()
                ->danger()
                ->title('Approval Failed')
                ->body('Error: ' . $e->getMessage())
                ->send();
        }
    }

    public function reject(): void
    {
        $data = $this->form->getState();

        if (empty($data['review_notes'])) {
            Notification::make()
                ->warning()
                ->title('Rejection Reason Required')
                ->body('Please provide a detailed reason for rejection.')
                ->send();
            return;
        }

        $this->record->update([
            'status' => 'rejected',
            'review_notes' => $data['review_notes'],
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // TODO: Send rejection email
        // Mail::to($this->record->email)->send(new ApplicationRejectedEmail($data['review_notes']));

        Notification::make()
            ->warning()
            ->title('Application Rejected')
            ->body('Rejection email will be sent to the applicant.')
            ->send();

        $this->redirect(ApplicationFormResource::getUrl('index'));
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        if ($data['status'] === 'approved') {
            $this->approve();
        } elseif ($data['status'] === 'rejected') {
            $this->reject();
        } else {
            $this->record->update([
                'status' => $data['status'],
                'review_notes' => $data['review_notes'],
                'reviewed_by' => auth()->id(),
            ]);

            Notification::make()
                ->info()
                ->title('Application Status Updated')
                ->body('The application is now marked as under review.')
                ->send();

            $this->redirect(ApplicationFormResource::getUrl('index'));
        }
    }

    protected function createUserAccount(string $password): array
    {
        $type = $this->record->applicant_type;

        if ($type === 'expert') {
            $expert = Expert::create([
                'name' => $this->record->full_name,
                'email' => $this->record->email,
                'phone' => $this->record->phone,
                'specialization' => $this->record->field_of_study,
                'expertise_areas' => $this->record->expertise_areas,
                'years_of_experience' => $this->record->years_of_experience,
                'bio' => $this->record->why_join,
                'linkedin_profile' => $this->record->linkedin_profile,
                'sample_work_url' => $this->record->sample_work_url,
                'application_status' => 'approved',
                'status' => 'active',
                'documents_verified' => true,
                'rating' => 0,
                'total_projects_completed' => 0,
                'total_earnings' => 0,
            ]);

            return ['id' => $expert->id, 'type' => Expert::class];
        }

        if ($type === 'tutor') {
            $tutor = Tutor::create([
                'name' => $this->record->full_name,
                'email' => $this->record->email,
                'phone' => $this->record->phone,
                'specialization' => $this->record->field_of_study,
                'expertise_areas' => $this->record->expertise_areas,
                'years_of_experience' => $this->record->years_of_experience,
                'bio' => $this->record->why_join,
                'linkedin_profile' => $this->record->linkedin_profile,
                'sample_work_url' => $this->record->sample_work_url,
                'application_status' => 'approved',
                'status' => 'active',
                'documents_verified' => true,
                'available' => true,
                'hourly_rate' => 0,
                'rating' => 0,
                'total_sessions_completed' => 0,
                'total_earnings' => 0,
            ]);

            return ['id' => $tutor->id, 'type' => Tutor::class];
        }

        if ($type === 'content_creator') {
            $creator = ContentCreator::create([
                'name' => $this->record->full_name,
                'email' => $this->record->email,
                'phone' => $this->record->phone,
                'specialization' => $this->record->field_of_study,
                'expertise_areas' => $this->record->expertise_areas,
                'years_of_experience' => $this->record->years_of_experience,
                'bio' => $this->record->why_join,
                'linkedin_profile' => $this->record->linkedin_profile,
                'portfolio_url' => $this->record->sample_work_url,
                'application_status' => 'approved',
                'status' => 'active',
                'documents_verified' => true,
                'rating' => 0,
                'total_courses_created' => 0,
                'total_earnings' => 0,
            ]);

            return ['id' => $creator->id, 'type' => ContentCreator::class];
        }

        throw new \Exception('Invalid applicant type: ' . $type);
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('submit')
                ->label('Submit Review')
                ->submit('submit')
                ->color('primary'),
        ];
    }
}
