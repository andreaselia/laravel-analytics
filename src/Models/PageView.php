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
        'country',
        'browser',
        'device',
    ];

    public function setSourceAttribute($value): void
    {
        $this->attributes['source'] = preg_replace('/https?:\/\/(www\.)?([a-z\-\.]+)\/?.*/i', '$2', $value);
    }

    public function setCountryAttribute($value): void
    {
        $this->attributes['country'] =  \Locale::getDisplayRegion($value, 'en');
    }

    public function getTypeAttribute($value): string
    {
        return \ucfirst($value);
    }

    protected static function newFactory(): Factory
    {
        return PageViewFactory::new();
    }
}
