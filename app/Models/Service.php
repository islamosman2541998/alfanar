<?php

namespace App\Models;

use App\Models\Concerns\HasCover;
use App\Models\Concerns\HasGallery;
use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasCover, HasGallery, HasPublishing, HasTranslations;

    protected $fillable = ['title', 'slug', 'excerpt', 'description', 'cover', 'meta_title', 'meta_description', 'is_featured', 'status', 'sort_order'];

    protected array $translatable = ['title', 'excerpt', 'description', 'meta_title', 'meta_description'];

    protected $casts = ['status' => 'boolean', 'is_featured' => 'boolean'];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function url(?string $locale = null): string
    {
        return route('services.show', ['locale' => $locale ?? app()->getLocale(), 'service' => $this->slug]);
    }
}
