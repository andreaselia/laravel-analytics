<?php

namespace AndreasElia\Analytics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use AndreasElia\Analytics\Database\Factories\PageViewFactory;

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
        $this->attributes['source'] = $value
            ? preg_replace('/https?:\/\/(www\.)?([a-z\-\.]+)\/?.*/i', '$2', $value)
            : $value;
    }

    public function setCountryAttribute($value): void
    {
        $this->attributes['country'] = \Locale::getDisplayRegion($value, 'en');
    }

    public function getTypeAttribute($value): string
    {
        return ucfirst($value);
    }

    public function scopeFilter($query, $period = 'today')
    {
        if ($period !== 'today') {
            [$interval, $unit] = explode('_', $period);

            return $query->where('created_at', '>=', now()->sub($unit, $interval));
        }

        return $query->whereDate('created_at', now()->today());
    }

    protected static function newFactory(): Factory
    {
        return PageViewFactory::new();
    }
}
