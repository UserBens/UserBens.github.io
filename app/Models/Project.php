<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'tech_stack'  => 'array',
        'is_featured' => 'boolean',
    ];

    // URL memakai slug, bukan id: /projects/portal-k3
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
