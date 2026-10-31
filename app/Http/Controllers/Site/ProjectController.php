<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::active()->whereHas('projects', fn ($q) => $q->active())->ordered()->get(['id', 'title', 'slug']);
        $current = $services->firstWhere('slug', $request->query('service'));

        seo()->set(txt('projects.title'))->page('projects')->breadcrumb(txt('projects.title'));

        if ($current) {
            seo()->set($current->tr('title').' - '.txt('projects.title'));
        }

        return view('site.projects.index', [
            'services' => $services,
            'current' => $current,
            'projects' => Project::active()->with('service')
                ->when($current, fn ($q) => $q->where('service_id', $current->id))
                ->ordered()
                ->paginate(12)
                ->withQueryString(),
        ]);
    }

    public function show(Project $project)
    {
        abort_unless($project->status, 404);

        $project->load('gallery', 'service');

        seo()->set(
            $project->tr('meta_title') ?: $project->tr('title'),
            $project->tr('meta_description') ?: ($project->tr('excerpt') ?: $project->tr('description')),
            $project->coverIsVideo() ? ($project->cover['poster'] ?? null) : ($project->cover['path'] ?? null),
            'article',
        )->breadcrumb(txt('projects.title'), route('projects.index'))->breadcrumb($project->tr('title'));

        return view('site.projects.show', [
            'project' => $project,
            'related' => Project::active()->with('service')
                ->whereKeyNot($project->id)
                ->orderByRaw('service_id = ? desc', [$project->service_id ?? 0])
                ->ordered()->take(8)->get(),
        ]);
    }
}
