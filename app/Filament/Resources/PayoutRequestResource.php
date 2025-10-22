<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayoutRequestResource\Pages;
use App\Models\PayoutRequest;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class PayoutRequestResource extends Resource
{
    protected static ?string $model = PayoutRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Financial';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Payout Requests';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Requester Information')
                    ->schema([
                        Forms\Components\Select::make('user_type')
                            ->label('User Type')
                            ->options([
                                'App\Models\Expert' => 'Expert',
                                'App\Models\Tutor' => 'Tutor',
                                'App\Models\ContentCreator' => 'Content Creator',
                            ])
                            ->required()
                            ->native(false)
                            ->reactive(),
                        Forms\Components\TextInput::make('user_id')
                            ->label('User ID')
                            ->numeric()
                            ->required()
                            ->helperText('Enter the ID of the expert/tutor/content creator'),
                        Forms\Components\TextInput::make('amount')
                            ->label('Payout Amount')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->minValue(10)
                            ->helperText('Minimum payout amount is $10'),
                    ])->columns(3),

                Forms\Components\Section::make('Payout Method & Details')
                    ->schema([
                        Forms\Components\Select::make('payout_method')
                            ->label('Payout Method')
                            ->options([
                                'bank_transfer' => 'Bank Transfer',
                                'mpesa' => 'M-Pesa',
                                'paypal' => 'PayPal',
                            ])
                            ->required()
                            ->native(false)
                            ->reactive()
                            ->live(),
                        Forms\Components\KeyValue::make('account_details')
                            ->label('Account Details')
                            ->keyLabel('Field')
                            ->valueLabel('Value')
                            ->helperText(function ($get) {
                                return match ($get('payout_method')) {
                                    'bank_transfer' => 'Enter: account_name, account_number, bank_name, bank_code',
                                    'mpesa' => 'Enter: phone_number, full_name',
                                    'paypal' => 'Enter: email, full_name',
                                    default => 'Select a payout method first',
                                };
                            })
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Status & Processing')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->default('pending')
                            ->native(false)
                            ->reactive()
                            ->live(),
                        Forms\Components\Select::make('batch_id')
                            ->label('Payout Batch')
                            ->relationship('batch', 'id')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->helperText('Optional: Assign to a payout batch'),
                        Forms\Components\DateTimePicker::make('requested_at')
                            ->label('Requested At')
                            ->default(now())
                            ->native(false)
                            ->displayFormat('M d, Y H:i')
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\Select::make('processed_by')
                            ->label('Processed By')
                            ->options(fn () => User::whereHas('roles', function($query) {
                                $query->whereIn('name', ['super_admin', 'admin']);
                            })->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->visible(fn ($get) => in_array($get('status'), ['approved', 'processing', 'completed', 'rejected'])),
                        Forms\Components\DateTimePicker::make('processed_at')
                            ->label('Processed At')
                            ->native(false)
                            ->displayFormat('M d, Y H:i')
                            ->visible(fn ($get) => in_array($get('status'), ['approved', 'processing', 'completed', 'rejected'])),
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->rows(3)
                            ->required(fn ($get) => $get('status') === 'rejected')
                            ->visible(fn ($get) => $get('status') === 'rejected')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Request #')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_type')
                    ->label('User Type')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('User ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('usd')
                    ->sortable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('usd')
                            ->label('Total'),
                    ]),
                Tables\Columns\TextColumn::make('payout_method')
                    ->label('Method')
                    ->formatStateUsing(fn ($state) => str($state)->title()->replace('_', ' '))
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'approved',
                        'primary' => 'processing',
                        'success' => 'completed',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch_id')
                    ->label('Batch')
                    ->placeholder('N/A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('processor.name')
                    ->label('Processed By')
                    ->placeholder('Not processed')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('requested_at')
                    ->label('Requested')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('processed_at')
                    ->label('Processed')
                    ->dateTime('M d, Y H:i')
                    ->placeholder('Not processed')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'rejected' => 'Rejected',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('user_type')
                    ->label('User Type')
                    ->options([
                        'App\Models\Expert' => 'Expert',
                        'App\Models\Tutor' => 'Tutor',
                        'App\Models\ContentCreator' => 'Content Creator',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('payout_method')
                    ->label('Payout Method')
                    ->options([
                        'bank_transfer' => 'Bank Transfer',
                        'mpesa' => 'M-Pesa',
                        'paypal' => 'PayPal',
                    ])
                    ->multiple(),
                Tables\Filters\Filter::make('amount')
                    ->form([
                        Forms\Components\TextInput::make('amount_from')
                            ->label('Amount From')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('amount_to')
                            ->label('Amount To')
                            ->numeric()
                            ->prefix('$'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['amount_from'], fn ($q, $amount) => $q->where('amount', '>=', $amount))
                            ->when($data['amount_to'], fn ($q, $amount) => $q->where('amount', '<=', $amount));
                    }),
                Tables\Filters\Filter::make('requested_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('requested_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('requested_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->visible(fn ($record) => $record->status === 'pending')
                        ->requiresConfirmation()
                        ->action(function ($record) {
                            $record->update([
                                'status' => 'approved',
                                'processed_by' => auth()->id(),
                                'processed_at' => now(),
                            ]);
                        }),
                    Tables\Actions\Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->visible(fn ($record) => $record->status === 'pending')
                        ->form([
                            Forms\Components\Textarea::make('rejection_reason')
                                ->label('Rejection Reason')
                                ->required()
                                ->rows(3),
                        ])
                        ->requiresConfirmation()
                        ->action(function ($record, array $data) {
                            $record->update([
                                'status' => 'rejected',
                                'rejection_reason' => $data['rejection_reason'],
                                'processed_by' => auth()->id(),
                                'processed_at' => now(),
                            ]);
                        }),
                    Tables\Actions\Action::make('mark_processing')
                        ->label('Mark as Processing')
                        ->icon('heroicon-o-arrow-path')
                        ->color('primary')
                        ->visible(fn ($record) => $record->status === 'approved')
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update(['status' => 'processing'])),
                    Tables\Actions\Action::make('mark_completed')
                        ->label('Mark as Completed')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn ($record) => in_array($record->status, ['approved', 'processing']))
                        ->requiresConfirmation()
                        ->action(fn ($record) => $record->update([
                            'status' => 'completed',
                            'processed_at' => now(),
                        ])),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve_selected')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                if ($record->status === 'pending') {
                                    $record->update([
                                        'status' => 'approved',
                                        'processed_by' => auth()->id(),
                                        'processed_at' => now(),
                                    ]);
                                }
                            });
                        }),
                    Tables\Actions\BulkAction::make('mark_processing_selected')
                        ->label('Mark as Processing')
                        ->icon('heroicon-o-arrow-path')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                if ($record->status === 'approved') {
                                    $record->update(['status' => 'processing']);
                                }
                            });
                        }),
                ]),
            ])
            ->defaultSort('requested_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Request Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('Request ID'),
                        Infolists\Components\TextEntry::make('user_type')
                            ->label('User Type')
                            ->formatStateUsing(fn ($state) => class_basename($state))
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('user_id')
                            ->label('User ID'),
                        Infolists\Components\TextEntry::make('amount')
                            ->label('Payout Amount')
                            ->money('usd'),
                        Infolists\Components\BadgeEntry::make('status')
                            ->colors([
                                'warning' => 'pending',
                                'info' => 'approved',
                                'primary' => 'processing',
                                'success' => 'completed',
                                'danger' => 'rejected',
                            ]),
                        Infolists\Components\TextEntry::make('batch_id')
                            ->label('Batch ID')
                            ->placeholder('Not assigned to batch'),
                    ])->columns(2),

                Infolists\Components\Section::make('Payout Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('payout_method')
                            ->label('Payout Method')
                            ->formatStateUsing(fn ($state) => str($state)->title()->replace('_', ' '))
                            ->badge()
                            ->color('primary'),
                        Infolists\Components\KeyValueEntry::make('account_details')
                            ->label('Account Details')
                            ->columnSpanFull(),
                    ]),

                Infolists\Components\Section::make('Processing Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('processor.name')
                            ->label('Processed By')
                            ->placeholder('Not processed'),
                        Infolists\Components\TextEntry::make('requested_at')
                            ->label('Requested At')
                            ->dateTime('M d, Y H:i'),
                        Infolists\Components\TextEntry::make('processed_at')
                            ->label('Processed At')
                            ->dateTime('M d, Y H:i')
                            ->placeholder('Not processed'),
                        Infolists\Components\TextEntry::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->visible(fn ($record) => $record->status === 'rejected')
                            ->placeholder('N/A')
                            ->columnSpanFull(),
                    ])->columns(3),

                Infolists\Components\Section::make('Timestamps')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->dateTime('M d, Y H:i'),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->dateTime('M d, Y H:i'),
                    ])->columns(2)
                    ->collapsible(),
            ]);
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
            'index' => Pages\ListPayoutRequests::route('/'),
            'create' => Pages\CreatePayoutRequest::route('/create'),
            'view' => Pages\ViewPayoutRequest::route('/{record}'),
            'edit' => Pages\EditPayoutRequest::route('/{record}/edit'),
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
