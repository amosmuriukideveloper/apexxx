<?php

namespace App\Filament\Expert\Resources\MyProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';
    protected static ?string $title = 'Messages';
    protected static ?string $icon = 'heroicon-o-chat-bubble-left-right';
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('message')
                    ->required()
                    ->rows(4)
                    ->placeholder('Type your message...'),
                
                Forms\Components\FileUpload::make('attachments')
                    ->multiple()
                    ->directory('message-attachments')
                    ->maxFiles(5)
                    ->maxSize(10240)
                    ->helperText('Upload files (max 5 files, 10MB each)'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('message')
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('sender_type')
                            ->label('From')
                            ->formatStateUsing(function ($record) {
                                $senderName = 'Unknown';
                                $role = '';
                                
                                if ($record->sender_type === 'App\\Models\\User') {
                                    $sender = \App\Models\User::find($record->sender_id);
                                    $senderName = $sender ? $sender->name : 'User';
                                    $role = $sender && $sender->hasRole('student') ? 'Student' : 'Admin';
                                } elseif ($record->sender_type === 'App\\Models\\Expert') {
                                    $sender = \App\Models\Expert::find($record->sender_id);
                                    $senderName = $sender ? $sender->name : 'Expert';
                                    $role = 'Expert (You)';
                                }
                                
                                return $senderName . ($role ? ' · ' . $role : '');
                            })
                            ->badge()
                            ->color(function ($record) {
                                if ($record->sender_id === Auth::id()) {
                                    return 'success';
                                }
                                return 'info';
                            })
                            ->weight('bold'),
                        
                        Tables\Columns\TextColumn::make('message')
                            ->label('')
                            ->html()
                            ->formatStateUsing(fn ($state) => nl2br(e($state)))
                            ->wrap()
                            ->grow(),
                        
                        Tables\Columns\TextColumn::make('attachments')
                            ->label('Attachments')
                            ->badge()
                            ->formatStateUsing(fn ($state) => is_array($state) ? count($state) . ' file(s)' : 'No attachments')
                            ->visible(fn ($record) => $record->attachments && count($record->attachments) > 0),
                    ]),
                    
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->since()
                        ->grow(false),
                ]),
            ])
            ->contentGrid([
                'sm' => 1,
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Send Message')
                    ->icon('heroicon-o-paper-airplane')
                    ->mutateFormDataUsing(function (array $data) {
                        $data['sender_type'] = 'App\\Models\\Expert';
                        $data['sender_id'] = Auth::id();
                        
                        return $data;
                    })
                    ->successNotificationTitle('Message sent successfully'),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => $record->sender_id === Auth::id()),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No messages yet')
            ->emptyStateDescription('Communicate with the student and admin here.')
            ->emptyStateIcon('heroicon-o-chat-bubble-left-right');
    }
}
