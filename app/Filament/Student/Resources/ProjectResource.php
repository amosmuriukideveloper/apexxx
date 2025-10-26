<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\ProjectResource\Pages;
use App\Models\Project;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'My Projects';
    protected static ?string $navigationGroup = 'Projects';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) Project::where('student_id', Auth::id())
            ->whereIn('status', ['in_progress', 'under_review'])
            ->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Project Details')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->columnSpan(2)
                                ->placeholder('e.g., Research Paper on Climate Change'),
                            
                            Forms\Components\Textarea::make('description')
                                ->required()
                                ->rows(4)
                                ->columnSpan(2)
                                ->placeholder('Describe your project requirements in detail...'),
                            
                            Forms\Components\Select::make('subject')
                                ->label('Subject Area')
                                ->options(Subject::pluck('name', 'name'))
                                ->required()
                                ->searchable()
                                ->preload(),
                            
                            Forms\Components\Select::make('project_type')
                                ->options([
                                    'essay' => 'Essay',
                                    'research_paper' => 'Research Paper',
                                    'case_study' => 'Case Study',
                                    'thesis' => 'Thesis/Dissertation',
                                    'assignment' => 'Assignment',
                                    'presentation' => 'Presentation',
                                    'report' => 'Report',
                                    'article' => 'Article',
                                    'other' => 'Other',
                                ])
                                ->required(),
                            
                            Forms\Components\Select::make('difficulty_level')
                                ->label('Difficulty Level')
                                ->options([
                                    'beginner' => 'Beginner',
                                    'intermediate' => 'Intermediate',
                                    'advanced' => 'Advanced',
                                    'expert' => 'Expert',
                                ])
                                ->required()
                                ->default('intermediate')
                                ->helperText('Select the appropriate difficulty level'),
                        ])
                        ->columns(2),
                    
                    Forms\Components\Wizard\Step::make('Requirements')
                        ->schema([
                            Forms\Components\TextInput::make('word_count')
                                ->numeric()
                                ->suffix('words')
                                ->helperText('Approximate word count')
                                ->reactive()
                                ->afterStateUpdated(function ($state, Forms\Set $set) {
                                    if ($state) {
                                        $pages = ceil($state / 250); // Approx 250 words per page
                                        $set('page_count', $pages);
                                    }
                                }),
                            
                            Forms\Components\TextInput::make('page_count')
                                ->numeric()
                                ->suffix('pages')
                                ->helperText('Or specify page count'),
                            
                            Forms\Components\DateTimePicker::make('deadline')
                                ->required()
                                ->minDate(now()->addHours(12))
                                ->maxDate(now()->addMonths(3))
                                ->native(false)
                                ->helperText('Project deadline')
                                ->columnSpan(2),
                            
                            Forms\Components\Textarea::make('admin_notes')
                                ->label('Special Instructions')
                                ->rows(4)
                                ->columnSpan(2)
                                ->placeholder('Any specific formatting, citation style, or other requirements...'),
                            
                            Forms\Components\FileUpload::make('attachments')
                                ->label('Reference Materials')
                                ->multiple()
                                ->disk('public')
                                ->directory('project-references')
                                ->maxFiles(5)
                                ->columnSpan(2)
                                ->helperText('Upload any reference materials, guidelines, or sample documents'),
                        ])
                        ->columns(2),
                    
                    Forms\Components\Wizard\Step::make('Review & Pricing')
                        ->schema([
                            Forms\Components\Placeholder::make('pricing_info')
                                ->label('Pricing Breakdown')
                                ->content(function (Forms\Get $get) {
                                    $wordCount = $get('word_count') ?? 0;
                                    $pageCount = $get('page_count') ?? 0;
                                    $complexityLevel = $get('complexity_level') ?? 'intermediate';
                                    $urgencyHours = $get('urgency_hours') ?? 168;
                                    
                                    // Calculate base price
                                    $basePrice = $pageCount ? ($pageCount * 10) : ($wordCount * 0.05);
                                    
                                    // Complexity multiplier
                                    $complexityMultiplier = match($complexityLevel) {
                                        'basic' => 1.0,
                                        'intermediate' => 1.3,
                                        'advanced' => 1.6,
                                        'expert' => 2.0,
                                        default => 1.0,
                                    };
                                    
                                    $complexityFee = $basePrice * ($complexityMultiplier - 1);
                                    
                                    // Urgency multiplier
                                    if ($urgencyHours <= 24) {
                                        $urgencyMultiplier = 2.0;
                                        $urgencyLabel = 'Rush (24hrs)';
                                    } elseif ($urgencyHours <= 48) {
                                        $urgencyMultiplier = 1.5;
                                        $urgencyLabel = 'Urgent (48hrs)';
                                    } elseif ($urgencyHours <= 72) {
                                        $urgencyMultiplier = 1.3;
                                        $urgencyLabel = 'Express (72hrs)';
                                    } else {
                                        $urgencyMultiplier = 1.0;
                                        $urgencyLabel = 'Standard';
                                    }
                                    
                                    $urgencyFee = $basePrice * ($urgencyMultiplier - 1);
                                    $totalPrice = $basePrice + $complexityFee + $urgencyFee;
                                    
                                    return new \Illuminate\Support\HtmlString('
                                        <div class="space-y-2 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                            <div class="flex justify-between">
                                                <span>Base Price:</span>
                                                <span class="font-semibold">$' . number_format($basePrice, 2) . '</span>
                                            </div>
                                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                                <span>+ Complexity Fee (' . ucfirst($complexityLevel) . '):</span>
                                                <span>$' . number_format($complexityFee, 2) . '</span>
                                            </div>
                                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                                <span>+ Urgency Fee (' . $urgencyLabel . '):</span>
                                                <span>$' . number_format($urgencyFee, 2) . '</span>
                                            </div>
                                            <div class="border-t border-gray-300 dark:border-gray-600 pt-2 mt-2"></div>
                                            <div class="flex justify-between text-lg font-bold">
                                                <span>Total Price:</span>
                                                <span class="text-green-600 dark:text-green-400">$' . number_format($totalPrice, 2) . '</span>
                                            </div>
                                        </div>
                                    ');
                                })
                                ->columnSpan(2),
                            
                            Forms\Components\Checkbox::make('agree_terms')
                                ->label('I agree to the terms and conditions')
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
                    ->default('pending_payment'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Project::where('student_id', Auth::id()))
            ->columns([
                Tables\Columns\TextColumn::make('project_number')
                    ->label('Project #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->description(fn ($record) => $record->project_type),
                
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subject')
                    ->badge()
                    ->searchable(),
                
                Tables\Columns\BadgeColumn::make('difficulty_level')
                    ->label('Difficulty')
                    ->colors([
                        'secondary' => 'beginner',
                        'primary' => 'intermediate',
                        'warning' => 'advanced',
                        'danger' => 'expert',
                    ]),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending_payment',
                        'warning' => 'awaiting_assignment',
                        'info' => 'assigned',
                        'primary' => 'in_progress',
                        'purple' => 'submitted',
                        'indigo' => 'under_review',
                        'success' => 'completed',
                        'danger' => 'revision_required',
                    ])
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state, '_'))),
                
                Tables\Columns\TextColumn::make('expert.name')
                    ->label('Expert')
                    ->placeholder('Not assigned'),
                
                Tables\Columns\TextColumn::make('deadline')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($record) => $record->isOverdue() ? 'danger' : null),
                
                Tables\Columns\TextColumn::make('budget')
                    ->money('USD')
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
                        'in_progress' => 'In Progress',
                        'under_review' => 'Under Review',
                        'completed' => 'Completed',
                        'revision_required' => 'Revision Required',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('pay')
                    ->label('Pay Now')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending_payment')
                    ->url(fn ($record) => route('filament.student.resources.projects.payment', ['record' => $record])),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'payment' => Pages\ProjectPayment::route('/{record}/payment'),
        ];
    }
}
