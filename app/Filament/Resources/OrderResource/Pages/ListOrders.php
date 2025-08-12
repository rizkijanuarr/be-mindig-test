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
        return [
            'all' => Tab::make('Semua')
                ->icon('heroicon-o-clipboard-document-list')
                ->badgeColor('gray')
                ->badge(Order::count())
                ->modifyQueryUsing(fn(Builder $query) => $query),
            'pending' => Tab::make('Pending')
                ->icon('heroicon-o-clock')
                ->badgeColor('warning')
                ->badge(Order::where('status', 'pending')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'pending')),
            'approved' => Tab::make('Approved')
                ->icon('heroicon-o-check-circle')
                ->badgeColor('success')
                ->badge(Order::where('status', 'approved')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'approved')),
            'rejected' => Tab::make('Rejected')
                ->icon('heroicon-o-x-circle')
                ->badgeColor('danger')
                ->badge(Order::where('status', 'rejected')->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'rejected')),
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
