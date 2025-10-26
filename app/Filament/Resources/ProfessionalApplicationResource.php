<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfessionalApplicationResource\Pages;
use App\Models\Expert;
use App\Models\Tutor;
use App\Models\ContentCreator;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProfessionalApplicationResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Applications';

    protected static ?string $modelLabel = 'Application';

    protected static ?string $pluralModelLabel = 'Applications';

    protected static ?string $navigationGroup = 'Applications';

    protected static ?int $navigationSort = 1;

    // Use a polymorphic approach - we'll handle all types
    protected static ?string $model = Expert::class;

    public static function getNavigationBadge(): ?string
    {
        $pendingCount = Expert::where('application_status', 'pending')->count() +
                       Tutor::where('application_status', 'pending')->count() +
                       ContentCreator::where('application_status', 'pending')->count();
        
        return $pendingCount > 0 ? (string) $pendingCount : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Application Information')
                    ->schema([
                        Forms\Components\Placeholder::make('applicant_type')
                            ->label('Applicant Type')
                            ->content(fn ($record) => ucfirst(class_basename($record))),
                        Forms\Components\Placeholder::make('submitted_at')
                            ->label('Submitted At')
                            ->content(fn ($record) => $record->created_at->format('M d, Y H:i')),
                    ])->columns(2),

                Forms\Components\Section::make('Applicant Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->disabled(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->disabled(),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->disabled(),
                    ])->columns(3),

                Forms\Components\Section::make('Professional Information')
                    ->schema([
                        Forms\Components\TextInput::make('specialization')
                            ->disabled()
                            ->visible(fn ($record) => $record instanceof Expert),
                        Forms\Components\TagsInput::make('expertise_areas')
                            ->disabled()
                            ->visible(fn ($record) => $record instanceof Expert || $record instanceof ContentCreator),
                        Forms\Components\TagsInput::make('subjects')
                            ->disabled()
                            ->visible(fn ($record) => $record instanceof Tutor),
                        Forms\Components\TextInput::make('years_of_experience')
                            ->numeric()
                            ->disabled()
                            ->visible(fn ($record) => $record instanceof Expert),
                        Forms\Components\TextInput::make('teaching_experience_years')
                            ->label('Teaching Experience (Years)')
                            ->numeric()
                            ->disabled()
                            ->visible(fn ($record) => $record instanceof Tutor),
                        Forms\Components\TextInput::make('portfolio_url')
                            ->url()
                            ->disabled()
                            ->visible(fn ($record) => $record instanceof ContentCreator),
                        Forms\Components\Textarea::make('bio')
                            ->rows(3)
                            ->disabled()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Uploaded Documents')
                    ->schema([
                        Forms\Components\FileUpload::make('cv_document')
                            ->label('CV/Resume')
                            ->downloadable()
                            ->openable()
                            ->disabled(),
                        Forms\Components\FileUpload::make('certificates')
                            ->label('Certificates')
                            ->multiple()
                            ->downloadable()
                            ->openable()
                            ->disabled(),
                        Forms\Components\FileUpload::make('id_document')
                            ->label('ID Document')
                            ->downloadable()
                            ->openable()
                            ->disabled(),
                        Forms\Components\Toggle::make('documents_verified')
                            ->label('Documents Verified')
                            ->helperText('Mark as verified after reviewing all documents'),
                    ])->columns(2),

                Forms\Components\Section::make('Review & Decision')
                    ->schema([
                        Forms\Components\Select::make('application_status')
                            ->label('Application Status')
                            ->options([
                                'pending' => 'Pending Review',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->live(),
                        Forms\Components\Textarea::make('rejection_reason')
                            ->rows(3)
                            ->visible(fn ($get) => $get('application_status') === 'rejected')
                            ->required(fn ($get) => $get('application_status') === 'rejected')
                            ->helperText('This will be sent to the applicant'),
                        Forms\Components\Textarea::make('admin_notes')
                            ->rows(3)
                            ->helperText('Internal notes (not visible to applicant)')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Applicant Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Applicant Type')
                    ->badge()
                    ->colors([
                        'primary' => 'Expert',
                        'success' => 'Tutor',
                        'warning' => 'Creator',
                    ])
                    ->getStateUsing(fn ($record) => class_basename($record))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function ($query) use ($search) {
                            // This will be handled in getEloquentQuery
                        });
                    }),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),
                Tables\Columns\TextColumn::make('expertise')
                    ->label('Expertise Areas')
                    ->getStateUsing(function ($record) {
                        if ($record instanceof Expert) {
                            return is_array($record->expertise_areas) 
                                ? implode(', ', $record->expertise_areas) 
                                : $record->specialization;
                        } elseif ($record instanceof Tutor) {
                            return is_array($record->subjects) 
                                ? implode(', ', $record->subjects) 
                                : 'N/A';
                        } elseif ($record instanceof ContentCreator) {
                            return is_array($record->expertise_areas) 
                                ? implode(', ', $record->expertise_areas) 
                                : 'N/A';
                        }
                        return 'N/A';
                    })
                    ->limit(30)
                    ->tooltip(function ($record) {
                        if ($record instanceof Expert && is_array($record->expertise_areas)) {
                            return implode(', ', $record->expertise_areas);
                        } elseif ($record instanceof Tutor && is_array($record->subjects)) {
                            return implode(', ', $record->subjects);
                        } elseif ($record instanceof ContentCreator && is_array($record->expertise_areas)) {
                            return implode(', ', $record->expertise_areas);
                        }
                        return null;
                    }),
                Tables\Columns\IconColumn::make('documents_verified')
                    ->boolean()
                    ->label('Docs')
                    ->tooltip(fn ($state) => $state ? 'Documents Verified' : 'Not Verified'),
                Tables\Columns\TextColumn::make('application_status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted At')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->since()
                    ->tooltip(fn ($record) => $record->created_at->format('M d, Y H:i:s')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('application_status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending'),
                Tables\Filters\SelectFilter::make('type')
                    ->label('Applicant Type')
                    ->options([
                        'expert' => 'Expert',
                        'tutor' => 'Tutor',
                        'creator' => 'Content Creator',
                    ]),
                Tables\Filters\TernaryFilter::make('documents_verified')
                    ->label('Documents Verified'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Submitted From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Submitted Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Submitted from ' . \Carbon\Carbon::parse($data['created_from'])->toFormattedDateString())
                                ->removeField('created_from');
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Submitted until ' . \Carbon\Carbon::parse($data['created_until'])->toFormattedDateString())
                                ->removeField('created_until');
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->application_status === 'pending')
                    ->action(function ($record) {
                        static::approveApplication($record);
                    }),
                Tables\Actions\Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')
                            ->required()
                            ->label('Reason for Rejection'),
                    ])
                    ->visible(fn ($record) => $record->application_status === 'pending')
                    ->action(function ($record, array $data) {
                        static::rejectApplication($record, $data['rejection_reason']);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulkApprove')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            foreach ($records as $record) {
                                if ($record->application_status === 'pending') {
                                    static::approveApplication($record);
                                }
                            }
                            Notification::make()
                                ->title('Applications approved successfully')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('bulkReject')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->form([
                            Forms\Components\Textarea::make('rejection_reason')
                                ->required()
                                ->label('Reason for Rejection'),
                        ])
                        ->action(function (Collection $records, array $data) {
                            foreach ($records as $record) {
                                if ($record->application_status === 'pending') {
                                    static::rejectApplication($record, $data['rejection_reason']);
                                }
                            }
                            Notification::make()
                                ->title('Applications rejected successfully')
                                ->danger()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s')
            ->emptyStateHeading('No applications found')
            ->emptyStateDescription('Applications will appear here once professionals register.')
            ->emptyStateIcon('heroicon-o-briefcase');
    }

    protected static function approveApplication($record): void
    {
        $record->update([
            'application_status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'status' => 'active',
        ]);

        // Assign appropriate role to user
        if ($record->user) {
            $roleName = match(class_basename($record)) {
                'Expert' => 'expert',
                'Tutor' => 'tutor',
                'ContentCreator' => 'creator',
                default => null,
            };
            
            if ($roleName) {
                $record->user->assignRole($roleName);
            }
        }

        // TODO: Send approval email

        Notification::make()
            ->title(class_basename($record) . ' application approved')
            ->success()
            ->send();
    }

    protected static function rejectApplication($record, string $reason): void
    {
        $record->update([
            'application_status' => 'rejected',
            'rejection_reason' => $reason,
            'status' => 'suspended',
        ]);

        // TODO: Send rejection email

        Notification::make()
            ->title(class_basename($record) . ' application rejected')
            ->danger()
            ->send();
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
            'index' => Pages\ListProfessionalApplications::route('/'),
            'view' => Pages\ViewProfessionalApplication::route('/{record}/{type}'),
            'edit' => Pages\EditProfessionalApplication::route('/{record}/{type}/edit'),
        ];
    }

    // This is a polymorphic resource - we handle multiple models
    public static function getEloquentQuery(): Builder
    {
        // This won't work directly, we'll override in the List page
        return parent::getEloquentQuery();
    }
}
