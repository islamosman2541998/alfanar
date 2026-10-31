<?php

namespace App\Models;

use App\Models\Concerns\HasCover;
use App\Models\Concerns\HasGallery;
use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasCover, HasGallery, HasPublishing, HasTranslations;

    protected $fillable = ['title', 'slug', 'description', 'cover', 'meta_title', 'meta_description', 'is_featured', 'status', 'sort_order'];

    protected array $translatable = ['title', 'description', 'meta_title', 'meta_description'];

    protected $casts = ['status' => 'boolean', 'is_featured' => 'boolean'];

    public function url(?string $locale = null): string
    {
        return route('pages.show', ['locale' => $locale ?? app()->getLocale(), 'page' => $this->slug]);
    }
}
