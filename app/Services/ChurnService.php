<?php
namespace App\Services;

use App\Models\Customer;
use Carbon\Carbon;

class ChurnService
{
    public function calculateChurnStats()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $activeCustomers = Customer::where('created_at', '<=', $thirtyDaysAgo)->count();
        $newCustomers = Customer::where('created_at', '>', $thirtyDaysAgo)->count();
        $churnedCustomers = Customer::where('churned_at', '>', $thirtyDaysAgo)->count();

        $totalCustomers = $activeCustomers + $newCustomers;

        $churnRate = $totalCustomers > 0 ? ($churnedCustomers / $totalCustomers) * 100 : 0;
        return [
            'activeCustomers' => $activeCustomers,
            'newCustomers' => $newCustomers,
            'churnedCustomers' => $churnedCustomers,
            'churnRate' => round($churnRate, 2),

        ];
    }
}
