<?php

namespace App\Filament\Resources\Transcription\Pages;

use App\Filament\Resources\Transcription\TranscriptionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTranscription extends EditRecord
{
    protected static string $resource = TranscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
