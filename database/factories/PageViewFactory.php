<?php

namespace Laravel\Analytics\Database\Factories;

use Laravel\Analytics\Models\PageView;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageViewFactory extends Factory
{
    protected $model = PageView::class;

    public function definition()
    {
        return [
            'ip_address' => $this->faker->colorName,
            'uri' => $this->faker->colorName,
            'source' => $this->faker->colorName,
            'country' => $this->faker->colorName,
            'browser' => $this->faker->colorName,
            'device' => $this->faker->colorName,
        ];
    }
}
