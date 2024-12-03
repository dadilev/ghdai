<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\Customer;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ChurnRateChart extends ChartWidget
{
    protected static ?string $heading = 'Churn Rate Trends';

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        for ($i = 0; $i <= 30; $i++) {
            $date = $thirtyDaysAgo->copy()->addDays($i);
            $churned = Customer::whereDate('churned_at', $date->toDateString())->count();
            $labels[] = $date->toFormattedDateString();
            $data[] = $churned;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Churned Subscribers',
                    'data' => $data,
                    'borderColor' => '#f87171',
                    'backgroundColor' => '#fee2e2',
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
