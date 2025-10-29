<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\TutoringRequestResource\Pages;
use App\Models\TutoringRequest;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TutoringRequestResource extends Resource
{
    protected static ?string $model = TutoringRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Request Tutoring';
    protected static ?string $navigationGroup = 'Tutoring';
    protected static ?int $navigationSort = 1;

    // Allow all students to view their tutoring requests - bypass policy
    public static function canViewAny(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Session Details')
                        ->schema([
                            Forms\Components\Select::make('subject_id')
                                ->label('Subject')
                                ->options(Subject::pluck('name', 'id'))
                                ->required()
                                ->searchable()
                                ->preload()
                                ->live()
                                ->columnSpan(2),
                            
                            Forms\Components\TextInput::make('specific_topic')
                                ->label('Specific Topic')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('e.g., Quadratic Equations, Python Loops, Essay Writing')
                                ->columnSpan(2),
                            
                            Forms\Components\Textarea::make('learning_goals')
                                ->label('Learning Goals & Objectives')
                                ->required()
                                ->rows(3)
                                ->placeholder('What do you want to achieve in this session?')
                                ->columnSpan(2),
                            
                            Forms\Components\Select::make('current_knowledge_level')
                                ->label('Current Knowledge Level')
                                ->options([
                                    'beginner' => 'Beginner - Just starting',
                                    'intermediate' => 'Intermediate - Have basic understanding',
                                    'advanced' => 'Advanced - Need help with complex topics',
                                ])
                                ->required()
                                ->columnSpan(2),
                            
                            Forms\Components\Textarea::make('specific_help_areas')
                                ->label('Specific Areas Needing Help')
                                ->rows(3)
                                ->placeholder('What specific concepts or problems are you struggling with?')
                                ->columnSpan(2),
                        ])
                        ->columns(2),
                    
                    Forms\Components\Wizard\Step::make('Schedule & Duration')
                        ->schema([
                            Forms\Components\DateTimePicker::make('preferred_date')
                                ->label('Preferred Date & Time')
                                ->required()
                                ->native(false)
                                ->minDate(now()->addHours(24))
                                ->maxDate(now()->addDays(30))
                                ->helperText('Sessions must be scheduled at least 24 hours in advance'),
                            
                            Forms\Components\DateTimePicker::make('alternative_date_1')
                                ->label('Alternative Date & Time #1')
                                ->native(false)
                                ->minDate(now()->addHours(24))
                                ->maxDate(now()->addDays(30)),
                            
                            Forms\Components\DateTimePicker::make('alternative_date_2')
                                ->label('Alternative Date & Time #2')
                                ->native(false)
                                ->minDate(now()->addHours(24))
                                ->maxDate(now()->addDays(30)),
                            
                            Forms\Components\Select::make('duration')
                                ->label('Session Duration')
                                ->options([
                                    30 => '30 minutes - $15',
                                    60 => '1 hour - $25',
                                    120 => '2 hours - $45',
                                ])
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, Forms\Set $set) {
                                    $rates = [30 => 15, 60 => 25, 120 => 45];
                                    $basePrice = $rates[$state] ?? 0;
                                    $platformFee = $basePrice * 0.15;
                                    $totalPrice = $basePrice + $platformFee;
                                    
                                    $set('base_price', $basePrice);
                                    $set('platform_fee', $platformFee);
                                    $set('total_price', $totalPrice);
                                }),
                            
                            Forms\Components\Placeholder::make('pricing')
                                ->label('Pricing Breakdown')
                                ->content(function (Forms\Get $get) {
                                    $duration = $get('duration') ?? 0;
                                    $rates = [30 => 15, 60 => 25, 120 => 45];
                                    $basePrice = $rates[$duration] ?? 0;
                                    $platformFee = $basePrice * 0.15;
                                    $totalPrice = $basePrice + $platformFee;
                                    
                                    return "Base Rate: $" . number_format($basePrice, 2) . "\n" .
                                           "Platform Fee (15%): $" . number_format($platformFee, 2) . "\n" .
                                           "Total: $" . number_format($totalPrice, 2);
                                })
                                ->columnSpan(2),
                            
                            Forms\Components\Hidden::make('base_price'),
                            Forms\Components\Hidden::make('platform_fee'),
                            Forms\Components\Hidden::make('total_price'),
                        ])
                        ->columns(2),
                    
                    Forms\Components\Wizard\Step::make('Materials')
                        ->schema([
                            Forms\Components\FileUpload::make('attachments')
                                ->label('Homework, Notes, or Reference Materials')
                                ->multiple()
                                ->disk('public')
                                ->directory('tutoring-materials')
                                ->maxFiles(5)
                                ->maxSize(10240)
                                ->helperText('Upload any materials that will help the tutor prepare')
                                ->columnSpan(2),
                            
                            Forms\Components\Textarea::make('additional_notes')
                                ->label('Additional Notes for Tutor')
                                ->rows(4)
                                ->placeholder('Any other information that might be helpful...')
                                ->columnSpan(2),
                            
                            Forms\Components\Checkbox::make('agree_terms')
                                ->label('I agree to the tutoring session terms and payment policy')
                                ->required()
                                ->accepted()
                                ->columnSpan(2),
                        ])
                        ->columns(2),
                ])
                ->columnSpan('full'),
                
                Forms\Components\Hidden::make('student_id')
                    ->default(Auth::id()),
                
                Forms\Components\Hidden::make('status')
                    ->default('pending_assignment'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('request_number')
                    ->label('Request #')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject')
                    ->badge()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('specific_topic')
                    ->label('Topic')
                    ->limit(30)
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => $state . ' min')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('preferred_date')
                    ->label('Preferred Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending_assignment',
                        'warning' => 'pending_tutor_response',
                        'info' => 'confirmed',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state, '_'))),
                
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total')
                    ->money('USD')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending_assignment' => 'Pending Assignment',
                        'pending_tutor_response' => 'Awaiting Tutor',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('join_session')
                    ->label('Join Session')
                    ->icon('heroicon-o-video-camera')
                    ->color('success')
                    ->url(fn ($record) => $record->google_meet_link)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->status === 'confirmed' && $record->google_meet_link),
                
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('student_id', Auth::id());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTutoringRequests::route('/'),
            'create' => Pages\CreateTutoringRequest::route('/create'),
            'view' => Pages\ViewTutoringRequest::route('/{record}'),
            'payment' => Pages\TutoringPayment::route('/{record}/payment'),
        ];
    }
}
