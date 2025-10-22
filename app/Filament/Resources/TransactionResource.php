<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Financial';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Transactions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Transaction Information')
                    ->schema([
                        Forms\Components\TextInput::make('transaction_number')
                            ->label('Transaction Number')
                            ->default(fn () => 'TXN-' . strtoupper(uniqid()))
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('user_type')
                            ->label('User Type')
                            ->options([
                                'App\Models\User' => 'User',
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
                            ->helperText('Enter the ID of the user/expert/tutor/content creator'),
                    ])->columns(3),

                Forms\Components\Section::make('Transaction Details')
                    ->schema([
                        Forms\Components\Select::make('transaction_type')
                            ->options([
                                'payment' => 'Payment',
                                'payout' => 'Payout',
                                'refund' => 'Refund',
                                'commission' => 'Commission',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('service_type')
                            ->label('Service Type')
                            ->options([
                                'App\Models\Project' => 'Project',
                                'App\Models\TutoringSession' => 'Tutoring Session',
                                'App\Models\Course' => 'Course',
                            ])
                            ->nullable()
                            ->native(false)
                            ->reactive(),
                        Forms\Components\TextInput::make('service_id')
                            ->label('Service ID')
                            ->numeric()
                            ->nullable()
                            ->helperText('Enter the ID of the related service'),
                    ])->columns(3),

                Forms\Components\Section::make('Amount Details')
                    ->schema([
                        Forms\Components\TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $commission = $state * 0.20; // 20% platform commission
                                $net = $state - $commission;
                                $set('platform_commission', number_format($commission, 2, '.', ''));
                                $set('net_amount', number_format($net, 2, '.', ''));
                            }),
                        Forms\Components\TextInput::make('platform_commission')
                            ->label('Platform Commission')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('net_amount')
                            ->label('Net Amount')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->default(0)
                            ->disabled()
                            ->dehydrated(),
                        Forms\Components\TextInput::make('currency')
                            ->label('Currency')
                            ->default('USD')
                            ->maxLength(3)
                            ->required(),
                    ])->columns(4),

                Forms\Components\Section::make('Payment Information')
                    ->schema([
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'mpesa' => 'M-Pesa',
                                'paypal' => 'PayPal',
                                'pesapal' => 'PesaPal',
                                'bank_transfer' => 'Bank Transfer',
                                'wallet' => 'Wallet',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\TextInput::make('payment_phone')
                            ->label('Payment Phone')
                            ->tel()
                            ->maxLength(255)
                            ->helperText('For M-Pesa transactions'),
                        Forms\Components\TextInput::make('payment_gateway_ref')
                            ->label('Payment Gateway Reference')
                            ->maxLength(255)
                            ->helperText('Reference from payment gateway'),
                    ])->columns(3),

                Forms\Components\Section::make('Status & Details')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->required()
                            ->default('pending')
                            ->native(false),
                        Forms\Components\DateTimePicker::make('processed_at')
                            ->label('Processed At')
                            ->native(false)
                            ->displayFormat('M d, Y H:i'),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->maxLength(1000)
                            ->columnSpanFull(),
                        Forms\Components\KeyValue::make('metadata')
                            ->label('Metadata')
                            ->keyLabel('Key')
                            ->valueLabel('Value')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_number')
                    ->label('Transaction #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->tooltip('Click to copy'),
                Tables\Columns\TextColumn::make('user_type')
                    ->label('User Type')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('User ID')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('transaction_type')
                    ->label('Type')
                    ->colors([
                        'success' => 'payment',
                        'info' => 'payout',
                        'warning' => 'refund',
                        'primary' => 'commission',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('service_type')
                    ->label('Service')
                    ->formatStateUsing(fn ($state) => $state ? class_basename($state) : 'N/A')
                    ->searchable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('amount')
                    ->money('usd')
                    ->sortable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money('usd')
                            ->label('Total'),
                    ]),
                Tables\Columns\TextColumn::make('platform_commission')
                    ->label('Commission')
                    ->money('usd')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('net_amount')
                    ->label('Net Amount')
                    ->money('usd')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->formatStateUsing(fn ($state) => str($state)->title()->replace('_', ' '))
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                        'gray' => 'refunded',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('processed_at')
                    ->label('Processed')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->placeholder('Not processed'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('transaction_type')
                    ->label('Transaction Type')
                    ->options([
                        'payment' => 'Payment',
                        'payout' => 'Payout',
                        'refund' => 'Refund',
                        'commission' => 'Commission',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'mpesa' => 'M-Pesa',
                        'paypal' => 'PayPal',
                        'pesapal' => 'PesaPal',
                        'bank_transfer' => 'Bank Transfer',
                        'wallet' => 'Wallet',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('user_type')
                    ->label('User Type')
                    ->options([
                        'App\Models\User' => 'User',
                        'App\Models\Expert' => 'Expert',
                        'App\Models\Tutor' => 'Tutor',
                        'App\Models\ContentCreator' => 'Content Creator',
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
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('From Date'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Until Date'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('mark_completed')
                    ->label('Mark Completed')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update([
                        'status' => 'completed',
                        'processed_at' => now(),
                    ])),
                Tables\Actions\Action::make('mark_failed')
                    ->label('Mark Failed')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update([
                        'status' => 'failed',
                        'processed_at' => now(),
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Transaction Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('transaction_number')
                            ->label('Transaction Number')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('user_type')
                            ->label('User Type')
                            ->formatStateUsing(fn ($state) => class_basename($state))
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('user_id')
                            ->label('User ID'),
                        Infolists\Components\BadgeEntry::make('transaction_type')
                            ->label('Transaction Type')
                            ->colors([
                                'success' => 'payment',
                                'info' => 'payout',
                                'warning' => 'refund',
                                'primary' => 'commission',
                            ]),
                        Infolists\Components\BadgeEntry::make('status')
                            ->colors([
                                'warning' => 'pending',
                                'success' => 'completed',
                                'danger' => 'failed',
                                'gray' => 'refunded',
                            ]),
                    ])->columns(2),

                Infolists\Components\Section::make('Service Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('service_type')
                            ->label('Service Type')
                            ->formatStateUsing(fn ($state) => $state ? class_basename($state) : 'N/A')
                            ->placeholder('N/A'),
                        Infolists\Components\TextEntry::make('service_id')
                            ->label('Service ID')
                            ->placeholder('N/A'),
                    ])->columns(2)
                    ->collapsible(),

                Infolists\Components\Section::make('Amount Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('amount')
                            ->label('Amount')
                            ->money('usd'),
                        Infolists\Components\TextEntry::make('platform_commission')
                            ->label('Platform Commission')
                            ->money('usd'),
                        Infolists\Components\TextEntry::make('net_amount')
                            ->label('Net Amount')
                            ->money('usd'),
                        Infolists\Components\TextEntry::make('currency')
                            ->label('Currency'),
                    ])->columns(4),

                Infolists\Components\Section::make('Payment Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('payment_method')
                            ->label('Payment Method')
                            ->formatStateUsing(fn ($state) => str($state)->title()->replace('_', ' '))
                            ->badge(),
                        Infolists\Components\TextEntry::make('payment_phone')
                            ->label('Payment Phone')
                            ->placeholder('N/A'),
                        Infolists\Components\TextEntry::make('payment_gateway_ref')
                            ->label('Gateway Reference')
                            ->placeholder('N/A')
                            ->copyable(),
                    ])->columns(3),

                Infolists\Components\Section::make('Additional Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('description')
                            ->placeholder('No description')
                            ->columnSpanFull(),
                        Infolists\Components\KeyValueEntry::make('metadata')
                            ->label('Metadata')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Timestamps')
                    ->schema([
                        Infolists\Components\TextEntry::make('processed_at')
                            ->label('Processed At')
                            ->dateTime('M d, Y H:i')
                            ->placeholder('Not processed'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('M d, Y H:i'),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime('M d, Y H:i'),
                    ])->columns(3)
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'view' => Pages\ViewTransaction::route('/{record}'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
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
