<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title'       => 'Project Contoh 1',
                'slug'        => 'project-contoh-1',
                'description' => 'Deskripsi singkat project pertama.',
                'tech_stack'  => ['Laravel', 'Tailwind CSS', 'PostgreSQL'],
                'url_repo'    => 'https://github.com/username/project-contoh-1',
                'is_featured' => true,
                'sort_order'  => 1,
            ],
            [
                'title'       => 'Project Contoh 2',
                'slug'        => 'project-contoh-2',
                'description' => 'Deskripsi singkat project kedua.',
                'tech_stack'  => ['Laravel', 'Vanilla JS'],
                'sort_order'  => 2,
            ],
        ];

        foreach ($projects as $data) {
            Project::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
