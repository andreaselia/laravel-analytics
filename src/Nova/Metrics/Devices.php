<?php

namespace AndreasElia\Analytics\Nova\Metrics;

use AndreasElia\Analytics\Models\PageView;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;
use Laravel\Nova\Metrics\PartitionResult;
use AndreasElia\Analytics\Nova\Ranges;

class Devices extends Partition
{
    use Ranges;

    public function calculate(NovaRequest $request): PartitionResult
    {
        return $this->count($request, PageView::class, 'device');
    }
}
