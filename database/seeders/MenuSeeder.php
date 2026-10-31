<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Service;
use Illuminate\Database\Seeder;

/** Default header and footer menus (skipped when menus already exist). */
class MenuSeeder extends Seeder
{
    protected array $titles = [
        'home' => ['الرئيسية', 'Home'],
        'about' => ['من نحن', 'About us'],
        'services.index' => ['خدماتنا', 'Services'],
        'projects.index' => ['أعمالنا', 'Projects'],
        'faq' => ['الأسئلة الشائعة', 'FAQ'],
        'contact' => ['تواصل معنا', 'Contact'],
    ];

    public function run(): void
    {
        if (MenuItem::exists()) {
            return;
        }

        // Header: Services opens a dropdown with the services
        foreach (['home', 'about', 'services.index', 'projects.index', 'faq', 'contact'] as $order => $route) {
            $item = $this->route('header', $route, $order);

            if ($route === 'services.index') {
                Service::active()->ordered()->get()->each(fn (Service $service, $i) => MenuItem::create([
                    'location' => 'header', 'parent_id' => $item->id, 'type' => 'service', 'linkable_id' => $service->id,
                    'title' => $service->title, 'sort_order' => $i,
                ]));
            }
        }

        // Footer column 1: quick links
        foreach (['home', 'about', 'projects.index', 'faq', 'contact'] as $order => $route) {
            $this->route('footer_1', $route, $order);
        }

        if ($page = Page::where('slug', 'how-we-work')->first()) {
            MenuItem::create(['location' => 'footer_1', 'type' => 'page', 'linkable_id' => $page->id, 'title' => $page->title, 'sort_order' => 10]);
        }

        // Footer column 2: featured services
        Service::active()->featured()->ordered()->get()->each(fn (Service $service, $i) => MenuItem::create([
            'location' => 'footer_2', 'type' => 'service', 'linkable_id' => $service->id, 'title' => $service->title, 'sort_order' => $i,
        ]));
    }

    protected function route(string $location, string $route, int $order): MenuItem
    {
        return MenuItem::create([
            'location' => $location,
            'type' => 'route',
            'route' => $route,
            'title' => ['ar' => $this->titles[$route][0], 'en' => $this->titles[$route][1]],
            'sort_order' => $order,
        ]);
    }
}
