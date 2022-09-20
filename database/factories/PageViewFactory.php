<?php

namespace AndreasElia\Analytics\Database\Factories;

use AndreasElia\Analytics\Models\PageView;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageViewFactory extends Factory
{
    protected $model = PageView::class;

    public function definition()
    {
        return [
            'session' => $this->faker->unique()->md5(),
            'uri'     => '/'.$this->faker->unique()->word(),
            'source'  => 'example.com',
            'country' => 'us',
            'browser' => 'chrome',
            'device'  => 'desktop',
        ];
    }
}
