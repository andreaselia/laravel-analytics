<?php

namespace Laravel\Analytics\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    use HasFactory;

    /** @var array */
    protected $fillable = [
        'ip_address',
        'uri',
    ];
}
