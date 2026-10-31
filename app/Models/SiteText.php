<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Support\SiteTexts;
use Illuminate\Database\Eloquent\Model;

class SiteText extends Model
{
    use HasTranslations;

    protected $fillable = ['key', 'group', 'value'];

    protected array $translatable = ['value'];

    protected static function booted(): void
    {
        static::saved(fn () => SiteTexts::flush());
        static::deleted(fn () => SiteTexts::flush());
    }
}
