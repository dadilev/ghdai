<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Enums\AdStatus;
use App\Models\Ads;
use App\Models\AdTemplates;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $adsTotal = Ads::count();
        $adTemplatesCount = AdTemplates::count();
        $completedAds = Ads::where('status', AdStatus::COMPLETED)->count();
        return [
            Stat::make('Ads', $adsTotal)->description($adsTotal. ' ads'),

            Stat::make('Ad Templates', $adTemplatesCount)->description($adTemplatesCount. ' ad templates')
                ->descriptionIcon('heroicon-m-user'),
            Stat::make('Completed Ads', $completedAds)->description($completedAds. ' complated ads')->descriptionIcon('heroicon-m-users'),
        ];
    }
}
