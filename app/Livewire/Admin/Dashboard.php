<?php

namespace App\Livewire\Admin;

use App\Models\Message;
use App\Models\Page;
use App\Models\Project;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Testimonial;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'tiles' => [
                ['label' => __('Services'), 'value' => Service::count(), 'icon' => 'bi-tools', 'route' => 'admin.services.index'],
                ['label' => __('Projects'), 'value' => Project::count(), 'icon' => 'bi-building', 'route' => 'admin.projects.index'],
                ['label' => __('Unread messages'), 'value' => Message::where('is_read', false)->count(), 'icon' => 'bi-envelope', 'route' => 'admin.messages'],
                ['label' => __('Messages this month'), 'value' => Message::where('created_at', '>=', now()->startOfMonth())->count(), 'icon' => 'bi-calendar3', 'route' => 'admin.messages'],
            ],
            'counts' => [
                ['label' => __('Slides'), 'value' => Slider::count(), 'route' => 'admin.sliders.index'],
                ['label' => __('Pages'), 'value' => Page::count(), 'route' => 'admin.pages.index'],
                ['label' => __('Testimonials'), 'value' => Testimonial::count(), 'route' => 'admin.testimonials'],
            ],
            'messages' => Message::with('service')->latest()->take(6)->get(),
            'projects' => Project::with('service')->latest()->take(5)->get(),
        ])->title(__('Dashboard'));
    }
}
