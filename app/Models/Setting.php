<?php

namespace App\Models;

use App\Support\Settings;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static function booted(): void
    {
        static::saved(fn () => Settings::flush());
        static::deleted(fn () => Settings::flush());
    }
}
