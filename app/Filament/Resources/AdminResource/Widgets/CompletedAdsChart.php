<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Enums\AdStatus;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CompletedAdsChart extends ChartWidget
{

    protected static ?string $heading = 'Completed Ads Chart';

    protected static ?string $maxHeight = '300px';
    public ?string $startDate = null;
    public ?string $endDate = null;


    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;


        $query = DB::table('ads')
            ->where('status', AdStatus::COMPLETED->value);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }
        switch ($activeFilter) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
            default:
                $query->whereMonth('created_at', now()->month);
        }

        $ads = $query
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('created_at')
            ->orderBy('created_at')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Completed Ads',
                    'data' => $ads->pluck('count')->toArray(),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $ads->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
