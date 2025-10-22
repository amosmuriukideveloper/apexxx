<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationFormResource\Pages;
use App\Filament\Resources\ApplicationFormResource\Schemas\ApplicationFormSchema;
use App\Filament\Resources\ApplicationFormResource\Tables\ApplicationFormTable;
use App\Models\ApplicationForm;
use App\Models\User;
use App\Models\Expert;
use App\Models\Tutor;
use App\Models\ContentCreator;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApplicationFormResource extends Resource
{
    protected static ?string $model = ApplicationForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Applications';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Applications';

    public static function form(Form $form): Form
    {
        return $form->schema(ApplicationFormSchema::getSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(ApplicationFormTable::getColumns())
            ->filters(ApplicationFormTable::getFilters())
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('mark_under_review')
                    ->label('Mark Under Review')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->visible(fn (ApplicationForm $record) => $record->status === 'pending')
                    ->action(function (ApplicationForm $record) {
                        $record->update([
                            'status' => 'under_review',
                            'reviewed_by' => auth()->id(),
                        ]);

                        Notification::make()
                            ->info()
                            ->title('Status Updated')
                            ->body('Application marked as under review.')
                            ->send();
                    }),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Application')
                    ->modalDescription('This will create a new account for the applicant and send them welcome credentials.')
                    ->visible(fn (ApplicationForm $record) => in_array($record->status, ['pending', 'under_review']))
                    ->action(function (ApplicationForm $record) {
                        try {
                            DB::beginTransaction();

                            // Generate random password
                            $password = Str::random(12);

                            // Create user account based on applicant type
                            $userData = self::createUserAccount($record, $password);

                            // Update application status
                            $record->update([
                                'status' => 'approved',
                                'reviewed_by' => auth()->id(),
                                'reviewed_at' => now(),
                                'applicant_id' => $userData['id'],
                                'applicant_type' => $userData['type'],
                            ]);

                            DB::commit();

                            // TODO: Send welcome email with credentials
                            // Mail::to($record->email)->send(new WelcomeEmail($password));

                            Notification::make()
                                ->success()
                                ->title('Application Approved')
                                ->body("Account created successfully. Email: {$record->email}, Password: {$password}")
                                ->persistent()
                                ->send();

                        } catch (\Exception $e) {
                            DB::rollBack();
                            
                            Notification::make()
                                ->danger()
                                ->title('Approval Failed')
                                ->body('Error: ' . $e->getMessage())
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Application')
                    ->modalDescription('Please provide a detailed reason for rejection.')
                    ->form([
                        \Filament\Forms\Components\Textarea::make('review_notes')
                            ->required()
                            ->rows(4)
                            ->label('Rejection Reason')
                            ->placeholder('Explain why this application is being rejected and what improvements are needed...')
                            ->helperText('This will be sent to the applicant.'),
                        \Filament\Forms\Components\Toggle::make('allow_reapplication')
                            ->label('Allow Reapplication')
                            ->helperText('Allow applicant to reapply after 30 days')
                            ->default(true),
                    ])
                    ->visible(fn (ApplicationForm $record) => in_array($record->status, ['pending', 'under_review']))
                    ->action(function (ApplicationForm $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                            'review_notes' => $data['review_notes'],
                        ]);

                        // TODO: Send rejection email
                        // Mail::to($record->email)->send(new ApplicationRejectedEmail($data['review_notes']));

                        Notification::make()
                            ->warning()
                            ->title('Application Rejected')
                            ->body('Rejection email will be sent to the applicant.')
                            ->send();
                    }),
            ])
            ->bulkActions(ApplicationFormTable::getBulkActions())
            ->defaultSort('created_at', 'desc');
    }

    /**
     * Create user account based on application type
     */
    protected static function createUserAccount(ApplicationForm $application, string $password): array
    {
        $type = $application->applicant_type;

        if ($type === 'expert') {
            $expert = Expert::create([
                'name' => $application->full_name,
                'email' => $application->email,
                'phone' => $application->phone,
                'specialization' => $application->field_of_study,
                'expertise_areas' => $application->expertise_areas,
                'years_of_experience' => $application->years_of_experience,
                'bio' => $application->why_join,
                'linkedin_profile' => $application->linkedin_profile,
                'sample_work_url' => $application->sample_work_url,
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
                'name' => $application->full_name,
                'email' => $application->email,
                'phone' => $application->phone,
                'specialization' => $application->field_of_study,
                'expertise_areas' => $application->expertise_areas,
                'years_of_experience' => $application->years_of_experience,
                'bio' => $application->why_join,
                'linkedin_profile' => $application->linkedin_profile,
                'sample_work_url' => $application->sample_work_url,
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
                'name' => $application->full_name,
                'email' => $application->email,
                'phone' => $application->phone,
                'specialization' => $application->field_of_study,
                'expertise_areas' => $application->expertise_areas,
                'years_of_experience' => $application->years_of_experience,
                'bio' => $application->why_join,
                'linkedin_profile' => $application->linkedin_profile,
                'portfolio_url' => $application->sample_work_url,
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplicationForms::route('/'),
            'view' => Pages\ViewApplicationForm::route('/{record}'),
            'review' => Pages\ReviewApplication::route('/{record}/review'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
