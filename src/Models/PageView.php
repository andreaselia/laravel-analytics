<?php

namespace Laravel\Analytics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Analytics\Database\Factories\PageViewFactory;

class PageView extends Model
{
    use HasFactory;

    /** @var array */
    protected $fillable = [
        'ip_address',
        'uri',
        'source',
        'country_code',
        'device_type',
    ];

    protected static function newFactory(): Factory
    {
        return PageViewFactory::new();
    }
}
