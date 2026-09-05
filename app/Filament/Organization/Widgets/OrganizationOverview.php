<?php

namespace App\Filament\Organization\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrganizationOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $organization = auth('organization')->user();

        return [
            Stat::make('Organization', $organization?->name ?? 'Unknown'),
            Stat::make('Organization ID', $organization?->getAuthIdentifier() ?? '-'),
            Stat::make('Email', $organization?->email ?? '-'),
        ];
    }
}