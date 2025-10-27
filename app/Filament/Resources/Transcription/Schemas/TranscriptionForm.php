<?php

namespace App\Filament\Resources\Transcription\Schemas;

use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class TranscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Forms\Components\Select::make('project_id')
                    ->relationship(
                        name: 'project',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('user_id', auth()->id())
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        \Filament\Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Project Name'),
                        \Filament\Forms\Components\Textarea::make('description')
                            ->maxLength(65535)
                            ->label('Description')
                            ->columnSpanFull(),
                    ])
                    ->createOptionUsing(function (array $data): int {
                        $data['user_id'] = auth()->id();
                        $project = \App\Models\Project::create($data);
                        return $project->id;
                    })
                    ->label('Project')
                    ->placeholder('Select a project or create a new one')
                    ->helperText('Select a project for this transcription'),
                \Filament\Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\FileUpload::make('audio_file_path')
                    ->required()
                    ->acceptedFileTypes(['audio/*'])
                    ->preserveFilenames()
                    ->directory('audio-files')
                    ->visibility('private'),
                \Filament\Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ])
                    ->required()
                    ->default('pending'),
                \Filament\Forms\Components\Hidden::make('transcription')
                    ->disabled(fn ($record) => $record?->status !== 'completed'),
                \Filament\Forms\Components\Textarea::make('error_message')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->disabled()
                    ->visible(fn ($record) => $record?->status === 'failed'),
            ]);
    }
}
