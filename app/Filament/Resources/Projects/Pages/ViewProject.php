<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('new_transcription')
                ->label('New Transcription')
                ->icon('heroicon-o-plus')
                ->url(fn () => route('filament.dashboard.resources.transcription.transcriptions.create', ['project_id' => $this->record->id])),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            \App\Filament\Resources\Projects\RelationManagers\TranscriptionsRelationManager::class,
        ];
    }
}
