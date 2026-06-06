<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    public static function canView(): bool
    {
        $user = auth()->user();

        return $user->roles()->count() > 0 && !$user->hasRole('customer');
    }
    protected function getStats(): array
    {
        return [
            Stat::make('Total Customers', User::whereDoesntHave('roles')->count() + User::whereHas('roles', function ($query) { $query->where('name', 'customer');})->count())
                ->description('All registered customers')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Products in Stock', Product::where('stock_qty', '>', 0)->sum('stock_qty'))
                ->description('Total items in stock')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),

            Stat::make('Total Orders', Product::where('stock_qty', '>', 0)->get()->count())
                ->description('Orders placed by customers')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning'),
        ];
    }
}
