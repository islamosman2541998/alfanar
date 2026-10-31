<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        seo()->set(txt('services.title'))->page('services')
            ->breadcrumb(txt('services.title'));

        return view('site.services.index', [
            'services' => Service::active()->ordered()->get(),
        ]);
    }

    public function show(Service $service)
    {
        abort_unless($service->status, 404);

        $service->load('gallery');

        seo()->set(
            $service->tr('meta_title') ?: $service->tr('title'),
            $service->tr('meta_description') ?: ($service->tr('excerpt') ?: $service->tr('description')),
            $service->coverIsVideo() ? ($service->cover['poster'] ?? null) : ($service->cover['path'] ?? null),
            'article',
        )->breadcrumb(txt('services.title'), route('services.index'))->breadcrumb($service->tr('title'));

        return view('site.services.show', [
            'service' => $service,
            'projects' => $service->projects()->active()->ordered()->take(9)->get(),
            'others' => Service::active()->whereKeyNot($service->id)->ordered()->get(['id', 'title', 'slug']),
        ]);
    }
}
