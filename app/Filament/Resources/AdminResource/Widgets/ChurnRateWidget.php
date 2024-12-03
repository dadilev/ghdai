<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\Customer;
use App\Models\User;
use App\Services\ChurnService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ChurnRateWidget extends BaseWidget
{
    protected ?string $heading = 'Churn Rate';

    protected function getCards(): array
    {
        $stats = app(ChurnService::class)->calculateChurnStats();

        return [
            BaseWidget\Card::make('Active Customers', $stats['activeCustomers']),
            BaseWidget\Card::make('New Customers (30 days)', $stats['newCustomers']),
            BaseWidget\Card::make('Churned Customers', $stats['churnedCustomers']),
            BaseWidget\Card::make('Churn Rate (%)', $stats['churnRate']),
        ];
    }
}
