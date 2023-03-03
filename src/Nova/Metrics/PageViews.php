<?php

namespace AndreasElia\Analytics\Nova\Metrics;

use AndreasElia\Analytics\Models\PageView;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Metrics\ValueResult;
use AndreasElia\Analytics\Nova\Ranges;

class PageViews extends Value
{
    use Ranges;

    public function calculate(NovaRequest $request): ValueResult
    {
        return $this->count($request, PageView::class)->allowZeroResult();
    }
}
