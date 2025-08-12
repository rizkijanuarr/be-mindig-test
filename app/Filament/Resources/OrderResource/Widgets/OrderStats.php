<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Models\Order;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class OrderStats extends BaseWidget
{
    use InteractsWithPageTable;

    public static function canView(): bool
    {
        $currentUser = Auth::user();
        return $currentUser->hasRole(['super_admin']);
    }

    protected function getStats(): array
    {
        // Ambil filter 'created_at' dari table filters jika ada (aman terhadap undefined key)
        $createdFilter = $this->tableFilters['created_at'] ?? null;

        if (is_array($createdFilter)) {
            $createdFrom = !empty($createdFilter['created_from'])
                ? Carbon::parse($createdFilter['created_from'])
                : now()->startOfMonth();

            $createdTo = !empty($createdFilter['created_until'])
                ? Carbon::parse($createdFilter['created_until'])
                : now()->endOfMonth();

            $hasCreatedRange = !empty($createdFilter['created_from']) && !empty($createdFilter['created_until']);
        } else {
            // default: awal - akhir bulan berjalan
            $createdFrom = now()->startOfMonth();
            $createdTo = now()->endOfMonth();
            $hasCreatedRange = false;
        }

        // Trend / chart data (aman jika kosong)
        $countTrend = Trend::model(Order::class)
            ->between(start: $createdFrom, end: $createdTo)
            ->perDay()
            ->count();

        $profitTrend = Trend::query(
            Order::query()->where('status', 'approved')
        )
            ->between(start: now()->startOfYear(), end: now()->endOfYear())
            ->perMonth()
            ->sum('profit');

        $totalTrend = Trend::query(
            Order::query()->where('status', 'approved')
        )
            ->between(start: now()->startOfYear(), end: now()->endOfYear())
            ->perMonth()
            ->sum('total');

        // Safely map trend collections to arrays for chart() — default empty array jika tidak ada map()
        $countSeries = (method_exists($countTrend, 'map'))
            ? $countTrend->map(fn(TrendValue $item) => $item->aggregate)->toArray()
            : [];

        $profitSeries = (method_exists($profitTrend, 'map'))
            ? $profitTrend->map(fn(TrendValue $item) => $item->aggregate)->toArray()
            : [];

        $totalSeries = (method_exists($totalTrend, 'map'))
            ? $totalTrend->map(fn(TrendValue $item) => $item->aggregate)->toArray()
            : [];

        // Hitung total/ profit menggunakan filter tanggal jika range dipilih
        $totalValue = Order::query()
            ->where('status', 'approved')
            ->when($hasCreatedRange, fn($q) => $q->whereDate('created_at', '>=', $createdFrom)->whereDate('created_at', '<=', $createdTo))
            ->sum('total');

        $profitValue = Order::query()
            ->where('status', 'approved')
            ->when($hasCreatedRange, fn($q) => $q->whereDate('created_at', '>=', $createdFrom)->whereDate('created_at', '<=', $createdTo))
            ->sum('profit');

        return [
            Stat::make('Orders', $this->getPageTableQuery()->count())
                ->chart($countSeries)
                ->icon('heroicon-o-shopping-bag')
                ->description('Orders in selected period.')
                ->descriptionColor('gray')
                ->color('success'),
            Stat::make('Total', 'IDR ' . number_format($totalValue, 0, ',', '.'))
                ->chart($totalSeries)
                ->icon('heroicon-o-banknotes')
                ->description('Total sales (approved).')
                ->descriptionColor('gray')
                ->color('success'),
            Stat::make('Profit', 'IDR ' . number_format($profitValue, 0, ',', '.'))
                ->chart($profitSeries)
                ->icon('heroicon-o-currency-dollar')
                ->description('Profit (approved).')
                ->descriptionColor('gray')
                ->color('success'),
        ];
    }

    protected function getTablePage(): string
    {
        return ListOrders::class;
    }
}
