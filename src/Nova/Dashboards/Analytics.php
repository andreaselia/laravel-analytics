<?php

namespace AndreasElia\Analytics\Nova\Dashboards;

use AndreasElia\Analytics\Nova\Metrics\Devices;
use AndreasElia\Analytics\Nova\Metrics\PageViews;
use AndreasElia\Analytics\Nova\Metrics\UniqueUsers;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Analytics extends Dashboard
{
    public function cards(): array
    {
        return [
            new PageViews,
            new UniqueUsers,
            new Devices,
        ];
    }
}
