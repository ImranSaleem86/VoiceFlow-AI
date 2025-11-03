<?php

namespace App\Filament\Resources\Transcription\Pages;

use App\Filament\Resources\Transcription\TranscriptionResource;
use Filament\Resources\Pages\CreateRecord;
use App\Jobs\processtranscriptions;

class CreateTranscription extends CreateRecord
{
    protected static string $resource = TranscriptionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = 'pending';
        $data['user_id'] = auth()->id();
        
        // If this is a project-related transcription, ensure the project exists
        if (isset($data['project_id']) && empty($data['project_id'])) {
            unset($data['project_id']);
        }
        
        return $data;
    }
    
    protected function afterCreate(): void
    {
        // You can add any post-creation logic here, like processing the audio file
        // For example, dispatch a job to process the audio in the background
        processtranscriptions::dispatch($this->record);

        // dd($this->record);
    }
}
