<?php

namespace App\Filament\Resources\Transcriptions\Pages;

use App\Filament\Resources\Transcriptions\TranscriptionsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTranscriptions extends CreateRecord
{
    protected static string $resource = TranscriptionsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = 'pending';
        $data['user_id'] = auth()->id();
        return $data;
    }
}
