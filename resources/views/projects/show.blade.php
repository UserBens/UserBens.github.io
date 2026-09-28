<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $project->title }}</title>
</head>
<body>
    <a href="{{ route('home') }}">&larr; Kembali</a>
    <h1>{{ $project->title }}</h1>
    <p>{{ $project->description }}</p>

    @if ($project->url_demo) <a href="{{ $project->url_demo }}">Demo</a> @endif
    @if ($project->url_repo) <a href="{{ $project->url_repo }}">Repo</a> @endif
</body>
</html>
