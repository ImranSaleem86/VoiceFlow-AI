<?php

namespace App\Filament\Resources\Transcription\Pages;

use App\Filament\Resources\Transcription\TranscriptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTranscription extends ListRecords
{
    protected static string $resource = TranscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
