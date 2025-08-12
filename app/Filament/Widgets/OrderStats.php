<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Carbon;

class OrderStats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return auth()->user()->hasRole(['super_admin']);
    }

    protected function getStats(): array
    {
        $createdFrom = new Carbon($this->filters['start_date'] ?? now()->startOfMonth());
        $createdTo = new Carbon($this->filters['end_date'] ?? now()->endOfMonth());

        $ordersQuery = Order::query()
            ->where('status', 'approved')
            ->when($this->filters['customer'], fn($query, $customer) => $query->where('user_id', $customer))
            ->whereBetween('created_at', [$createdFrom, $createdTo]);

        $orderStats = $ordersQuery->clone()->get(['total', 'profit']);

        $totalProfit = $orderStats->sum('profit');
        $countOrders = $orderStats->count();

        return [
            Stat::make('Orders', $countOrders)
                ->icon('heroicon-o-shopping-bag')
                ->description('Total orders'),
            Stat::make('Profit', 'IDR ' . number_format($totalProfit, 0, ',', '.'))
                ->icon('heroicon-o-banknotes')
                ->description('Total profit'),
            Stat::make('Customer',
                    User::query()->when(
                        $this->filters['start_date'] && $this->filters['end_date'],
                        fn($query) => $query->whereHas('orders', fn($query) => $query->whereBetween('created_at', [$createdFrom, $createdTo]))
                    )->count())
                ->icon('heroicon-o-user-group')
                ->description('Total customers'),
        ];
    }
}
