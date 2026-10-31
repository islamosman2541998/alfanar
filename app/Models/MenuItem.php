<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Support\Menu;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasTranslations;

    public const LOCATIONS = ['header', 'footer_1', 'footer_2'];

    public const TYPES = ['route', 'page', 'service', 'project', 'url', 'none'];

    /** Built-in site pages that can be linked from the menu. */
    public const ROUTES = [
        'home' => 'Home',
        'services.index' => 'Services',
        'projects.index' => 'Projects',
        'about' => 'About us',
        'contact' => 'Contact us',
        'faq' => 'FAQ',
    ];

    protected $fillable = ['location', 'parent_id', 'title', 'type', 'route', 'linkable_id', 'url', 'new_tab', 'status', 'sort_order'];

    protected array $translatable = ['title'];

    protected $casts = ['status' => 'boolean', 'new_tab' => 'boolean'];

    protected static function booted(): void
    {
        static::saved(fn () => Menu::flush());
        static::deleted(fn () => Menu::flush());
    }

    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order');
    }
}
