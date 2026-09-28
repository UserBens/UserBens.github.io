<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    // ---------- Publik ----------

    public function index(): View
    {
        $projects = Project::orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project): View
    {
        return view('projects.show', compact('project'));
    }

    // ---------- Admin (dilindungi middleware auth di routes) ----------

    public function create(): View
    {
        return view('projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['title']);

        $project = Project::create($data);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project berhasil ditambahkan.');
    }

    public function edit(Project $project): View
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $project->update($this->validated($request));

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('home')
            ->with('success', 'Project berhasil dihapus.');
    }

    // ---------- Helper ----------

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title'       => ['required', 'string', 'max:150'],
            'description' => ['required', 'string'],
            'tech_stack'  => ['nullable', 'string'],      // input "Laravel, Tailwind, PostgreSQL"
            'image'       => ['nullable', 'string', 'max:255'],
            'url_demo'    => ['nullable', 'url'],
            'url_repo'    => ['nullable', 'url'],
            'is_featured' => ['boolean'],
            'sort_order'  => ['nullable', 'integer', 'min:0'],
        ]);

        // "Laravel, Tailwind" -> ["Laravel","Tailwind"]
        $data['tech_stack'] = collect(explode(',', $data['tech_stack'] ?? ''))
            ->map(fn ($t) => trim($t))
            ->filter()
            ->values()
            ->all();

        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order']  = $data['sort_order'] ?? 0;

        return $data;
    }

    private function uniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $i = 2;

        while (Project::where('slug', $slug)->exists()) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }
}
