<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>My Portofolio</title>
</head>
<body>
    <h1>Project Saya</h1>

    @foreach ($projects as $project)
        <article>
            <h2><a href="{{ route('projects.show', $project) }}">{{ $project->title }}</a></h2>
            <p>{{ Str::limit($project->description, 120) }}</p>
            <small>{{ implode(' · ', $project->tech_stack ?? []) }}</small>
        </article>
    @endforeach
</body>
</html>
