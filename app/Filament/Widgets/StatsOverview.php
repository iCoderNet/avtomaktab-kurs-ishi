<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use App\Models\Instructor;
use App\Models\Payment;
use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make("Faol o'quvchilar", Student::where('status', 'active')->count())
                ->description("Jami: " . Student::count())
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make("Faol instruktorlar", Instructor::where('status', 'active')->count())
                ->description("Jami: " . Instructor::count())
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),

            Stat::make("Oylik daromad", number_format(
                Payment::whereMonth('payment_date', now()->month)->where('status', 'completed')->sum('amount'), 0, '.', ' '
            ) . " so'm")
                ->description("Bu oy yig'ildi")
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),

            Stat::make("Mavjud avtomobillar", Vehicle::where('status', 'available')->count())
                ->description("Jami: " . Vehicle::count())
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'),
        ];
    }
}
