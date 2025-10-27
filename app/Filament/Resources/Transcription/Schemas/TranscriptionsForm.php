<?php

namespace App\Filament\Resources\Transcriptions\Schemas;

use App\Models\Transcriptions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class TranscriptionsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                FileUpload::make('audio_file_path')
                    ->label('Audio File')
                    ->required()
                    ->acceptedFileTypes(['audio/*'])
                    ->directory('transcriptions/audio')
                    ->maxsize(10240)
                    ->downloadable()
                    ->openable()
                    ->previewable()
                    ->helperText('Maximum file size is 10MB'),
                Select::make('status')
                    ->options(Transcriptions::getStatusOptions())
                    ->default('pending')
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->in(transcriptions::getStatusOptions())
                    ->hidden(fn (string $operation) => $operation !== 'edit'),
                Textarea::make('transcription')
                    ->label('Transcription')
                    ->nullable()
                    ->rows(5)
                    ->maxLength(65535)
                    ->columnSpan('full')
                    ->hidden(fn (string $operation) => $operation !== 'edit'),
                Textarea::make('error_message')
                    ->label('Error Message')
                    ->nullable()
                    ->rows(3)
                    ->maxLength(1000)
                    ->columnSpan('full')
                    ->hidden(fn (string $operation) => $operation !== 'edit'),
            ]);
    }
}
