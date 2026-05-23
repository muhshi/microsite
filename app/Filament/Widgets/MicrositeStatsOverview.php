<?php

namespace App\Filament\Widgets;

use App\Models\Microsite;
use App\Models\ShortLink;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MicrositeStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Microsite', Microsite::count())
                ->description('Semua portal yang terdaftar')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('gray'),

            Stat::make('Microsite Aktif', Microsite::where('is_published', true)->count())
                ->description('Portal live dan dapat diakses publik')
                ->descriptionIcon('heroicon-m-globe-alt')
                ->color('success'),

            Stat::make('Total Klik Short Link', ShortLink::sum('clicks'))
                ->description('Total lalu lintas klik short link')
                ->descriptionIcon('heroicon-m-cursor-arrow-rays')
                ->color('info'),
        ];
    }
}
