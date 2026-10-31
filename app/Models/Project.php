<?php

namespace App\Models;

use App\Models\Concerns\HasCover;
use App\Models\Concerns\HasGallery;
use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasCover, HasGallery, HasPublishing, HasTranslations;

    protected $fillable = ['service_id', 'title', 'slug', 'excerpt', 'description', 'location', 'year', 'cover', 'meta_title', 'meta_description', 'is_featured', 'status', 'sort_order'];

    protected array $translatable = ['title', 'excerpt', 'description', 'location', 'meta_title', 'meta_description'];

    protected $casts = ['status' => 'boolean', 'is_featured' => 'boolean'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function url(?string $locale = null): string
    {
        return route('projects.show', ['locale' => $locale ?? app()->getLocale(), 'project' => $this->slug]);
    }
}
