<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\ToolsResource;
use App\Models\Order;
use App\Models\Tools;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconPosition;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListOrders extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // Implementasi Tab Filter

    public function getTabs(): array
    {
        $user = Auth::user();
        $isSuper = $user?->hasRole('super_admin');
        $userId = $user?->id;

        $base = Order::query()->when(! $isSuper, fn ($q) => $q->where('user_id', $userId));

        $allCount = (clone $base)->count();
        $pendingCount = (clone $base)->where('status', 'pending')->count();
        $approvedCount = (clone $base)->where('status', 'approved')->count();
        $rejectedCount = (clone $base)->where('status', 'rejected')->count();

        return [
            'all' => Tab::make('Semua')
                ->icon('heroicon-o-clipboard-document-list')
                ->badgeColor('gray')
                ->badge($allCount)
                ->modifyQueryUsing(function (Builder $query) use ($isSuper, $userId) {
                    return $query->when(! $isSuper, fn ($q) => $q->where('user_id', $userId));
                }),
            'pending' => Tab::make('Pending')
                ->icon('heroicon-o-clock')
                ->badgeColor('warning')
                ->badge($pendingCount)
                ->modifyQueryUsing(function (Builder $query) use ($isSuper, $userId) {
                    return $query->when(! $isSuper, fn ($q) => $q->where('user_id', $userId))
                        ->where('status', 'pending');
                }),
            'approved' => Tab::make('Approved')
                ->icon('heroicon-o-check-circle')
                ->badgeColor('success')
                ->badge($approvedCount)
                ->modifyQueryUsing(function (Builder $query) use ($isSuper, $userId) {
                    return $query->when(! $isSuper, fn ($q) => $q->where('user_id', $userId))
                        ->where('status', 'approved');
                }),
            'rejected' => Tab::make('Rejected')
                ->icon('heroicon-o-x-circle')
                ->badgeColor('danger')
                ->badge($rejectedCount)
                ->modifyQueryUsing(function (Builder $query) use ($isSuper, $userId) {
                    return $query->when(! $isSuper, fn ($q) => $q->where('user_id', $userId))
                        ->where('status', 'rejected');
                }),
        ];
    }

    // Default Tab Filter

    public function getDefaultActiveTab(): string|int|null
    {
        return 'all';
    }

    // Implementasi statistik order

    public function getHeaderWidgets(): array
    {
        return [
            OrderResource\Widgets\OrderStats::class,
        ];
    }
}
