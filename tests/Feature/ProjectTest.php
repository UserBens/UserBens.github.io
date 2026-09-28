<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    private function makeProject(array $override = []): Project
    {
        return Project::create(array_merge([
            'title'       => 'Portal K3',
            'slug'        => 'portal-k3',
            'description' => 'Aplikasi HSE internal.',
            'tech_stack'  => ['Laravel', 'PostgreSQL'],
        ], $override));
    }

    public function test_homepage_menampilkan_daftar_project(): void
    {
        $this->makeProject();

        $this->get('/')
            ->assertOk()
            ->assertSee('Portal K3');
    }

    public function test_halaman_detail_project_bisa_dibuka(): void
    {
        $this->makeProject();

        $this->get('/projects/portal-k3')
            ->assertOk()
            ->assertSee('Aplikasi HSE internal.');
    }
}
