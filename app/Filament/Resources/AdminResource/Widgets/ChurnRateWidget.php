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
        // Define the period
        $startOfMonth = Carbon::now()->startOfMonth()->subMonth();
        $endOfMonth = Carbon::now()->startOfMonth();

        // Query the total users at the start of the period
        $usersAtStartOfPeriod = DB::table('customers')
            ->where('created_at', '<', $startOfMonth)
            ->count();

        // Query the total active users at the end of the period
        $usersAtEndOfPeriod = DB::table('customers')
            ->where('active', true)
            ->where('updated_at', '<', $endOfMonth)
            ->count();

        // Calculate Churn Rate
        if ($usersAtStartOfPeriod > 0) {
            $churnRate = (($usersAtStartOfPeriod - $usersAtEndOfPeriod) / $usersAtStartOfPeriod) * 100;
        }else{
            $churnRate = 0;
        }
        $totalUsers = User::count();
        $totalCustomers = Customer::count();
        return [
            Stat::make('Churn Rate last month', $churnRate. '%')
                ->description($usersAtEndOfPeriod. ' users')
                ->descriptionIcon('heroicon-m-arrow-trending-down'),
            Stat::make('Total users', $totalUsers)->description($totalUsers. ' users')
                ->descriptionIcon('heroicon-m-user'),
            Stat::make('Total Customers', $totalCustomers)->description($totalCustomers. ' customers')->descriptionIcon('heroicon-m-users'),
        ];
    }
}
