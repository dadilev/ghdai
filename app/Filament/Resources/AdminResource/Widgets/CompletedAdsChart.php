<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Enums\AdStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CompletedAdsChart extends ChartWidget
{

    protected static ?string $heading = 'Completed Ads Chart';

    protected static ?string $maxHeight = '300px';
    public ?string $startDate = null;
    public ?string $endDate = null;



    protected function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\DatePicker::make('startDate')
                ->label('Start Date')
                ->reactive()
                ->afterStateUpdated(fn () => $this->updateChart()),
            \Filament\Forms\Components\DatePicker::make('endDate')
                ->label('End Date')
                ->reactive()
                ->afterStateUpdated(fn () => $this->updateChart()),
        ];
    }

    protected function getData(): array
    {
        $query = DB::table('ads')
            ->whereNotNull('created_at');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
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
