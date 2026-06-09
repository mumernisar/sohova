<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationLabel = 'Leads';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Contact Information')
                ->description('Primary contact details for this lead.')
                ->schema([
                    Forms\Components\TextInput::make('name')->required(),
                    Forms\Components\TextInput::make('email')->email()->required(),
                    Forms\Components\TextInput::make('phone'),
                    Forms\Components\TextInput::make('company'),
                    Forms\Components\Textarea::make('message')->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Enrichment Data')
                ->description('Company data gathered via enrichment.')
                ->schema([
                    Forms\Components\TextInput::make('company_domain'),
                    Forms\Components\TextInput::make('company_size')->numeric(),
                    Forms\Components\TextInput::make('company_industry'),
                    Forms\Components\Select::make('enrichment_status')
                        ->options([
                            'pending'  => 'Pending',
                            'enriched' => 'Enriched',
                            'failed'   => 'Failed',
                        ]),
                ])->columns(2),

            Forms\Components\Section::make('Sales Pipeline')
                ->description('Current status and outreach tracking.')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'new'           => 'New',
                            'contacted'     => 'Contacted',
                            'qualified'     => 'Qualified',
                            'disqualified'  => 'Disqualified',
                        ])
                        ->required(),
                    Forms\Components\Placeholder::make('welcome_email_sent_at')
                        ->label('Welcome Email Sent')
                        ->content(fn (?Lead $record): string =>
                            $record?->welcome_email_sent_at
                                ? $record->welcome_email_sent_at->format('M j, Y \a\t g:i A')
                                : 'Not sent yet'
                        ),
                ])->columns(2),

            Forms\Components\Section::make('Raw Payload')
                ->description('Original data received from the API submission.')
                ->collapsed()
                ->schema([
                    Forms\Components\KeyValue::make('raw_payload')
                        ->columnSpanFull()
                        ->disabled(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('company')->searchable(),
                Tables\Columns\TextColumn::make('company_size')
                    ->label('Size')->numeric()->sortable(),
                Tables\Columns\TextColumn::make('enrichment_status')
                    ->label('Enrichment')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'  => 'warning',
                        'enriched' => 'success',
                        'failed'   => 'danger',
                        default    => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new'           => 'gray',
                        'contacted'     => 'info',
                        'qualified'     => 'success',
                        'disqualified'  => 'danger',
                        default         => 'gray',
                    }),
                Tables\Columns\IconColumn::make('welcome_email_sent_at')
                    ->label('Welcomed')->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->sortable()->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new'           => 'New',
                        'contacted'     => 'Contacted',
                        'qualified'     => 'Qualified',
                        'disqualified'  => 'Disqualified',
                    ]),
                Tables\Filters\SelectFilter::make('enrichment_status')
                    ->options([
                        'pending'  => 'Pending',
                        'enriched' => 'Enriched',
                        'failed'   => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit'   => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
