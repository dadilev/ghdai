<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\Customer;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ChurnRateWidget extends BaseWidget
{
    protected ?string $heading = 'Churn Rate';

    protected function getStats(): array
    {
        $churnRate = 0;
        // Define the period (current month)
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Total users at the start of the period
        $usersAtStart = DB::table('customers')
            ->where('active', true)
            ->where('created_at', '<=', $startOfMonth)
            ->count() ?? 0;

        // Active users at the end of the period
        $activeUsersAtEnd = DB::table('customers')
            ->where('active', false)
            ->where('created_at', '<=', $endOfMonth)
            ->count() ?? 0;

        // Calculate Churn Rate
        if ($usersAtStart != 0 && $activeUsersAtEnd != 0) {
            $churnRate = (int)((($usersAtStart - $activeUsersAtEnd) / $usersAtStart) * 100);
        }

        $totalUsers = User::count();
        $totalCustomers = Customer::count();
        return [
            Stat::make('Churn Rate last month', $churnRate. '%')
                ->description($activeUsersAtEnd. ' users')
                ->descriptionIcon('heroicon-m-arrow-trending-down'),
            Stat::make('Total users', $totalUsers)->description($totalUsers. ' users')
                ->descriptionIcon('heroicon-m-user'),
            Stat::make('Total Customers', $totalCustomers)->description($totalCustomers. ' customers')->descriptionIcon('heroicon-m-users'),
        ];
    }
}
