<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use App\Models\Transcription;
use Illuminate\Support\Facades\Auth;

class TranscriptionCompletion extends ChartWidget
{
    protected ?string $heading = 'Transcription Status';
    protected static ?int $sort = 2;

    public ?string $filter = 'today'; // Default filter

    protected function getData(): array
    {
        $user = Auth::user();
        $activeFilter = $this->filter;

        // determine the data range based on the active filter

        $query = Transcription::where('user_id', $user->id);

        switch ($activeFilter) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'yesterday':
                $query->whereDate('created_at', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                break;
            case 'last_month':
                $query->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()]);
                break;
            default:
                break;    
        }

        $pending = (clone $query)->where('status', 'pending')->count();
        $in_progress = (clone $query)->where('status', 'in_progress')->count();
        $completed = (clone $query)->where('status', 'completed')->count();
        $failed = (clone $query)->where('status', 'failed')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Transcription Status',
                    'data' => [$pending, $in_progress, $completed, $failed],
                    'backgroundColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(251, 191, 36)',
                        'rgb(34, 197, 94)',
                        'rgb(239, 68, 68)',
                    ],
                    'borderColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(251, 191, 36)',
                        'rgb(34, 197, 94)',
                        'rgb(239, 68, 68)',
                    ],
                    'borderWidth' => 1,
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => ['Pending', 'In Progress', 'Completed', 'Failed'],
        ];
    }

    protected function getFilters(): array
    {
        return [
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            'this_week' => 'This Week',
            'this_month' => 'This Month',
            'last_month' => 'Last Month',
        ];
    }   


    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'maintainAspectRatio' => true,
            ],
        ];
    }
}
