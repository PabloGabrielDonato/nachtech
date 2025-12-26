<?php

namespace App\Filament\Widgets;

use App\Models\Device;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class IncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Ingresos últimos 6 meses';
    
    // 1. ESTO HACE QUE OCUPE TODO EL ANCHO
    //protected static string | int | array | null $columnSpan = 'full';

    protected function getData(): array
    {
        // 2. CORRECCIÓN DE DATOS
        // Filtramos explícitamente por el estado 'sold' (vendido)
        $data = Trend::query(
                Device::query()->where('status', 'sold')
            )
            ->dateColumn('sold_at') // Importante: Trend necesita saber qué fecha usar
            ->between(
                start: now()->subMonths(6),
                end: now(),
            )
            ->perMonth()
            ->sum('sale_price');

        return [
            'datasets' => [
                [
                    'label' => 'Ventas',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#3b82f6',
                    'fill' => 'start', // Opcional: sombrea el área bajo la linea
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}