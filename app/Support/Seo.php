<?php

namespace App\Support;

/**
 * Per-request SEO data filled by controllers and rendered by the site layout.
 */
class Seo
{
    public ?string $title = null;

    public ?string $description = null;

    public ?string $image = null;

    public string $type = 'website';

    public array $breadcrumbs = [];

    /** Use the title / description configured for a built-in page in Settings > SEO. */
    public function page(string $key): static
    {
        $this->title = setting("seo_{$key}_title") ?: $this->title;
        $this->description = setting("seo_{$key}_description") ?: $this->description;

        return $this;
    }

    public function set(?string $title = null, ?string $description = null, ?string $image = null, ?string $type = null): static
    {
        $this->title = $title ?: $this->title;
        $this->description = $description ?: $this->description;
        $this->image = $image ?: $this->image;
        $this->type = $type ?: $this->type;

        return $this;
    }

    public function breadcrumb(string $label, ?string $url = null): static
    {
        $this->breadcrumbs[] = ['label' => $label, 'url' => $url];

        return $this;
    }

    public function fullTitle(): string
    {
        $site = (string) setting('site_name', config('app.name'));

        if ($this->title) {
            return $this->title === $site ? $site : $this->title.' | '.$site;
        }

        return (string) setting('seo_title', $site);
    }

    public function metaDescription(): string
    {
        $text = $this->description ?: (string) setting('seo_description', '');

        return \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags($text))), 160, '');
    }

    public function imageUrl(): ?string
    {
        return media_url($this->image ?: setting('og_image') ?: setting('logo'));
    }

    /** Business, website and breadcrumb structured data for the current page. */
    public function graph(): array
    {
        $socials = collect(['facebook', 'instagram', 'tiktok', 'youtube', 'x', 'linkedin'])
            ->map(fn ($key) => setting($key))->filter()->values()->all();

        $graph = [
            array_filter([
                '@type' => setting('schema_type', 'HomeAndConstructionBusiness'),
                '@id' => url('/').'#business',
                'name' => setting('site_name'),
                'description' => setting('seo_description'),
                'url' => route('home'),
                'logo' => media_url(setting('logo')),
                'image' => $this->imageUrl(),
                'telephone' => setting('phone'),
                'email' => setting('email'),
                'priceRange' => setting('schema_price_range'),
                'openingHours' => 'Mo-Su 00:00-23:59',
                'address' => setting('address') ? ['@type' => 'PostalAddress', 'streetAddress' => setting('address'), 'addressCountry' => 'EG'] : null,
                'sameAs' => $socials ?: null,
            ]),
            [
                '@type' => 'WebSite',
                '@id' => url('/').'#website',
                'name' => setting('site_name'),
                'url' => route('home'),
                'inLanguage' => app()->getLocale(),
            ],
        ];

        if ($this->breadcrumbs) {
            $crumbs = array_merge([['label' => txt('nav.home'), 'url' => route('home')]], $this->breadcrumbs);
            $graph[] = [
                '@type' => 'BreadcrumbList',
                'itemListElement' => collect($crumbs)->values()->map(fn ($crumb, $i) => [
                    '@type' => 'ListItem',
                    'position' => $i + 1,
                    'name' => $crumb['label'],
                    'item' => $crumb['url'] ?? url()->current(),
                ])->all(),
            ];
        }

        return ['@graph' => $graph];
    }

    /** JSON-LD script tag (schema.org context added here, outside Blade). */
    public static function jsonLd(array $data): string
    {
        $json = json_encode(['@context' => 'https://schema.org'] + $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);

        return '<script type="application/ld+json">'.$json.'</script>';
    }
}
