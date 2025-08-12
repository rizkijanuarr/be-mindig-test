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
use Illuminate\Support\Facades\Auth;

class CustomerOrderStats extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return auth()->user()->hasRole(['customer']);
    }

    protected function getStats(): array
    {
        $userId = Auth::id();

        $base = Order::query()->where('user_id', $userId);
        $pending = (clone $base)->where('status', 'pending')->count();
        $approved = (clone $base)->where('status', 'approved')->count();
        $rejected = (clone $base)->where('status', 'rejected')->count();

        return [
            Stat::make('Pending', $pending)
                ->icon('heroicon-o-clock')
                ->color('warning')
                ->description('Transaksi pending'),
            Stat::make('Approved', $approved)
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->description('Transaksi approved'),
            Stat::make('Rejected', $rejected)
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->description('Transaksi rejected'),
        ];
    }
}
