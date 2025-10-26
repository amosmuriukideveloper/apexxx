<?php

namespace App\Filament\Tutor\Resources\MyTutoringSessionResource\Pages;

use App\Filament\Tutor\Resources\MyTutoringSessionResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;

class CompleteSession extends Page
{
    protected static string $resource = MyTutoringSessionResource::class;
    protected static string $view = 'filament.tutor.pages.complete-session';
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill();
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Session Completion')
                    ->schema([
                        Forms\Components\Toggle::make('student_attended')
                            ->label('Student Attended Session')
                            ->required()
                            ->live()
                            ->helperText('Mark whether the student joined the session'),
                        
                        Forms\Components\RichEditor::make('session_notes')
                            ->label('Session Notes & Summary')
                            ->required()
                            ->columnSpan(2)
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                            ])
                            ->placeholder('Provide a detailed summary of what was covered, student progress, and any recommendations...')
                            ->helperText('These notes will be shared with the student'),
                        
                        Forms\Components\Textarea::make('topics_covered')
                            ->label('Topics Covered')
                            ->rows(3)
                            ->placeholder('List the main topics and concepts covered during the session...')
                            ->columnSpan(2),
                        
                        Forms\Components\Textarea::make('homework_assignments')
                            ->label('Homework/Practice Assignments')
                            ->rows(3)
                            ->placeholder('Assign practice problems or homework for the student...')
                            ->columnSpan(2),
                        
                        Forms\Components\FileUpload::make('additional_resources')
                            ->label('Additional Learning Resources')
                            ->multiple()
                            ->disk('public')
                            ->directory('session-resources')
                            ->maxFiles(10)
                            ->helperText('Upload PDFs, worksheets, practice problems, etc.')
                            ->columnSpan(2),
                        
                        Forms\Components\TextInput::make('session_recording_link')
                            ->label('Session Recording Link (Optional)')
                            ->url()
                            ->placeholder('https://...')
                            ->helperText('If you recorded the session, provide the link here')
                            ->columnSpan(2),
                        
                        Forms\Components\Textarea::make('next_session_recommendations')
                            ->label('Recommendations for Next Session')
                            ->rows(3)
                            ->placeholder('What should be covered in the next session if student books again...')
                            ->columnSpan(2),
                        
                        Forms\Components\Select::make('student_engagement_level')
                            ->label('Student Engagement Level')
                            ->options([
                                'excellent' => 'Excellent - Very engaged and participated',
                                'good' => 'Good - Engaged and asked questions',
                                'fair' => 'Fair - Some engagement',
                                'poor' => 'Poor - Limited engagement',
                            ])
                            ->required(),
                        
                        Forms\Components\Select::make('comprehension_level')
                            ->label('Student Comprehension Level')
                            ->options([
                                'excellent' => 'Excellent - Grasped concepts quickly',
                                'good' => 'Good - Understood with some help',
                                'fair' => 'Fair - Struggled but made progress',
                                'poor' => 'Poor - Significant difficulty',
                            ])
                            ->required(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }
    
    public function submit()
    {
        $data = $this->form->getState();
        
        $this->record->update([
            'status' => 'completed',
            'completed_at' => now(),
            'session_notes' => $data['session_notes'],
            'topics_covered' => $data['topics_covered'] ?? null,
            'homework_assignments' => $data['homework_assignments'] ?? null,
            'additional_resources' => $data['additional_resources'] ?? null,
            'session_recording_link' => $data['session_recording_link'] ?? null,
            'next_session_recommendations' => $data['next_session_recommendations'] ?? null,
            'student_engagement_level' => $data['student_engagement_level'],
            'comprehension_level' => $data['comprehension_level'],
            'student_attended' => $data['student_attended'],
        ]);
        
        // Send notification to student
        Notification::make()
            ->title('Session Completed')
            ->success()
            ->body('Session materials and notes have been shared with the student.')
            ->send();
        
        return redirect()->route('filament.tutor.resources.my-tutoring-sessions.index');
    }
}
