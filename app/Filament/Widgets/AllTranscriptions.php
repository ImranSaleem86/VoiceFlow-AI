<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transcription;
use App\Models\User;
use App\Models\Project;

class AllTranscriptions extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        return [
            Stat::make('Total Transcriptions', Transcription::where('user_id', $user->id)->count())
            ->icon('heroicon-o-microphone'),
            Stat::make('Total Users', User::count())
            ->icon('heroicon-o-user'),
            Stat::make('Total Projects', Project::count())
            ->icon('heroicon-o-document-text'),
        ];
    }
}
