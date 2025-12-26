<?php
namespace App\Filament\Widgets;

use App\Models\Device;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Stock', Device::where('status', 'stock')->count())
                ->color('success'),
            Stat::make('En Reparación', Device::where('status', 'repair')->count())
                ->color('warning'),
            Stat::make('Ventas Totales', '$'.Device::sum('sale_price'))
                ->color('primary'),
        ];
    }
}