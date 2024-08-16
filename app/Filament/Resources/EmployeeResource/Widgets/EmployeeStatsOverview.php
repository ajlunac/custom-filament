<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use App\Models\Country;
use App\Models\Employee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {   

        $co = Country::where('country_code', 'co')->withCount('employees')->first();
        $mx = Country::where('country_code', 'mx')->withCount('employees')->first();

        return [
            //
            Stat::make('Total Epleados', Employee::all()->count()),
            Stat::make($co->name . ' Empleados', $co->employees_count),
            Stat::make($mx->name . ' Empleados', $mx->employees_count),
        ];
    }
}
