<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    public function sitemap()
    {
        $xml = Cache::remember('sitemap.xml', now()->addHour(), function () {
            $urls = [];

            foreach (array_keys(locales()) as $locale) {
                foreach (['home', 'services.index', 'projects.index', 'about', 'contact', 'faq'] as $route) {
                    $urls[] = ['loc' => route($route, ['locale' => $locale]), 'lastmod' => null, 'priority' => $route === 'home' ? '1.0' : '0.8'];
                }

                foreach ([Service::class, Project::class, Page::class] as $model) {
                    $model::active()->get(['slug', 'updated_at'])->each(function ($record) use (&$urls, $locale) {
                        $urls[] = ['loc' => $record->url($locale), 'lastmod' => $record->updated_at?->toAtomString(), 'priority' => '0.7'];
                    });
                }
            }

            return view('site.sitemap', ['urls' => $urls])->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots()
    {
        $lines = setting('seo_indexing', '1')
            ? ['User-agent: *', 'Disallow: /admin', 'Disallow: /livewire', '', 'Sitemap: '.route('sitemap')]
            : ['User-agent: *', 'Disallow: /'];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
