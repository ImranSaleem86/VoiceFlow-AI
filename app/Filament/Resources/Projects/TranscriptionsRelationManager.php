<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Models\Transcription;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table as FilamentTable;
use Illuminate\Database\Eloquent\Builder;

class TranscriptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transcriptions';
    
    protected static ?string $model = Transcription::class;

    protected static ?string $recordTitleAttribute = 'title';

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->schema([
                \Filament\Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ])
                    ->required(),
                \Filament\Forms\Components\Textarea::make('transcription')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'processing' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['project_id'] = $this->getOwnerRecord()->id;
                        return $data;
                    })
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('Download Transcription')
                    ->label('Download Transcription')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->visible(fn ($record) => $record->status === 'completed')
                    ->action(fn () => null),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = Auth()::id;
                        $data['status'] = 'pending';
                        return $data;
                    })
            ])
            ;
    }

}
