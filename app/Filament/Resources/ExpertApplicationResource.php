<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpertApplicationResource\Pages;
use App\Models\Expert;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class ExpertApplicationResource extends Resource
{
    protected static ?string $model = Expert::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Expert Applications';

    protected static ?string $navigationGroup = 'Applications';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('application_status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Applicant Information')
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

                Forms\Components\Section::make('Professional Details')
                    ->schema([
                        Forms\Components\TextInput::make('specialization')
                            ->disabled(),
                        Forms\Components\TagsInput::make('expertise_areas')
                            ->disabled(),
                        Forms\Components\TextInput::make('years_of_experience')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\Textarea::make('bio')
                            ->rows(3)
                            ->disabled()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Documents')
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
                            ->helperText('Mark as verified after reviewing documents'),
                    ])->columns(2),

                Forms\Components\Section::make('Application Status')
                    ->schema([
                        Forms\Components\Select::make('application_status')
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
                            ->required(fn ($get) => $get('application_status') === 'rejected'),
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('specialization')
                    ->searchable(),
                Tables\Columns\TextColumn::make('years_of_experience')
                    ->label('Experience')
                    ->suffix(' years')
                    ->sortable(),
                Tables\Columns\IconColumn::make('documents_verified')
                    ->boolean()
                    ->label('Docs'),
                Tables\Columns\TextColumn::make('application_status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Applied')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('application_status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\TernaryFilter::make('documents_verified')
                    ->label('Documents Verified'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Expert $record) => $record->application_status === 'pending')
                    ->action(function (Expert $record) {
                        $record->update([
                            'application_status' => 'approved',
                            'approved_by' => auth()->id(),
                            'approved_at' => now(),
                            'status' => 'active',
                        ]);

                        // Assign expert role to user
                        if ($record->user) {
                            $record->user->assignRole('expert');
                        }

                        // TODO: Send approval email

                        Notification::make()
                            ->title('Expert application approved')
                            ->success()
                            ->send();
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
                    ->visible(fn (Expert $record) => $record->application_status === 'pending')
                    ->action(function (Expert $record, array $data) {
                        $record->update([
                            'application_status' => 'rejected',
                            'rejection_reason' => $data['rejection_reason'],
                            'status' => 'suspended',
                        ]);

                        // TODO: Send rejection email

                        Notification::make()
                            ->title('Expert application rejected')
                            ->danger()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListExpertApplications::route('/'),
            'view' => Pages\ViewExpertApplication::route('/{record}'),
            'edit' => Pages\EditExpertApplication::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('application_status', ['pending', 'approved', 'rejected']);
    }
}
