<?php

namespace App\Support;

use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/**
 * Resolves menu items into a cached, locale-independent tree per location.
 * Node: ['title' => [ar, en], 'type', 'route', 'slug', 'url', 'new_tab', 'children']
 */
class Menu
{
    protected const CACHE_KEY = 'site.menus';

    public static function tree(string $location): array
    {
        return Cache::rememberForever(static::CACHE_KEY, fn () => static::build())[$location] ?? [];
    }

    public static function flush(): void
    {
        Cache::forget(static::CACHE_KEY);
    }

    /** URL of a resolved node for the active locale. */
    public static function url(array $node): ?string
    {
        return match ($node['type']) {
            'route' => Route::has($node['route']) ? route($node['route']) : null,
            'page' => route('pages.show', ['page' => $node['slug']]),
            'service' => route('services.show', ['service' => $node['slug']]),
            'project' => route('projects.show', ['project' => $node['slug']]),
            'url' => $node['url'],
            default => null,
        };
    }

    public static function title(array $node): string
    {
        $title = $node['title'][app()->getLocale()] ?? '';

        return filled($title) ? $title : (string) collect($node['title'])->first(fn ($v) => filled($v), '');
    }

    public static function isActive(array $node): bool
    {
        if (collect($node['children'])->contains(fn ($child) => static::isActive($child))) {
            return true;
        }

        $url = static::url($node);
        $current = url()->current();

        if (! $url) {
            return false;
        }

        return $url === $current
            || ($node['type'] === 'route' && $node['route'] !== 'home' && str_starts_with($current, rtrim($url, '/').'/'));
    }

    protected static function build(): array
    {
        $items = MenuItem::query()->where('status', true)->orderBy('sort_order')->orderBy('id')->get();

        $slugs = [
            'page' => Page::active()->pluck('slug', 'id'),
            'service' => Service::active()->pluck('slug', 'id'),
            'project' => Project::active()->pluck('slug', 'id'),
        ];

        return collect(MenuItem::LOCATIONS)
            ->mapWithKeys(fn ($location) => [
                $location => static::branch($items->where('location', $location)->whereNull('parent_id'), $items, $slugs),
            ])
            ->all();
    }

    protected static function branch(Collection $nodes, Collection $items, array $slugs): array
    {
        return $nodes->map(function (MenuItem $item) use ($items, $slugs) {
            // Skip links to records that were removed or hidden.
            if (isset($slugs[$item->type]) && ! isset($slugs[$item->type][$item->linkable_id])) {
                return null;
            }

            return [
                'title' => $item->title,
                'type' => $item->type,
                'route' => $item->route,
                'slug' => $slugs[$item->type][$item->linkable_id] ?? null,
                'url' => $item->url,
                'new_tab' => $item->new_tab,
                'children' => static::branch($items->where('parent_id', $item->id), $items, $slugs),
            ];
        })->filter()->values()->all();
    }
}
