<?php

namespace App\Models;

use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasTranslations;
use App\Services\MediaService;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasPublishing, HasTranslations;

    protected $fillable = ['name', 'position', 'content', 'rating', 'photo', 'status', 'sort_order'];

    protected array $translatable = ['name', 'position', 'content'];

    protected $casts = ['status' => 'boolean', 'rating' => 'integer'];

    protected static function booted(): void
    {
        static::deleted(fn ($model) => app(MediaService::class)->delete(['path' => $model->photo]));
    }
}
