<?php

namespace AndreasElia\Analytics\Nova\Metrics;

use AndreasElia\Analytics\Models\PageView;
use AndreasElia\Analytics\Nova\Ranges;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Metrics\PartitionResult;

class Devices extends Partition
{
    use Ranges;

    public function calculate(NovaRequest $request): PartitionResult
    {
        return $this->count($request, PageView::class, 'device');
    }
}
