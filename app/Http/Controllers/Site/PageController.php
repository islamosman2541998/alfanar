<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\Page;
use App\Models\Stat;
use App\Models\Testimonial;

class PageController extends Controller
{
    public function about()
    {
        seo()->set(txt('about.title'), txt('home.about.text'), setting('about_image'))->page('about')->breadcrumb(txt('about.title'));

        return view('site.about', [
            'features' => Feature::active()->ordered()->get(),
            'stats' => Stat::active()->ordered()->get(),
            'testimonials' => Testimonial::active()->ordered()->get(),
        ]);
    }

    public function contact()
    {
        seo()->set(txt('contact.title'))->page('contact')->breadcrumb(txt('contact.title'));

        return view('site.contact');
    }

    public function faq()
    {
        seo()->set(txt('faq.title'))->page('faq')->breadcrumb(txt('faq.title'));

        return view('site.faq', [
            'faqs' => Faq::active()->ordered()->get(),
        ]);
    }

    public function show(Page $page)
    {
        abort_unless($page->status, 404);

        $page->load('gallery');

        seo()->set(
            $page->tr('meta_title') ?: $page->tr('title'),
            $page->tr('meta_description') ?: $page->tr('description'),
            $page->coverIsVideo() ? ($page->cover['poster'] ?? null) : ($page->cover['path'] ?? null),
        )->breadcrumb($page->tr('title'));

        return view('site.page', ['page' => $page]);
    }
}
