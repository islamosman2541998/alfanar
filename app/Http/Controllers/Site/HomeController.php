<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Project;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Stat;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function redirect()
    {
        $locale = setting('default_locale', config('app.locale'));

        return redirect()->route('home', ['locale' => array_key_exists($locale, locales()) ? $locale : 'ar']);
    }

    public function index()
    {
        seo()->page('home');

        return view('site.home', [
            'sliders' => Slider::active()->ordered()->get(),
            'features' => setting('home_about', '1') ? Feature::active()->ordered()->take(4)->get() : collect(),
            'services' => setting('home_services', '1') ? Service::active()->ordered()->take(10)->get() : collect(),
            'projects' => setting('home_projects', '1')
                ? Project::active()->with('service')->orderByDesc('is_featured')->ordered()->take(10)->get()
                : collect(),
            'stats' => Stat::active()->ordered()->get(),
            'testimonials' => setting('home_testimonials', '1') ? Testimonial::active()->ordered()->get() : collect(),
        ]);
    }
}
