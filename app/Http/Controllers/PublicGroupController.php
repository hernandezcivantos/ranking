<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PublicGroupController extends Controller
{
    public function show(\App\Models\Group $group)
    {
        $leagues = $group->leagues()->orderBy('name')->get();

        return Inertia::render('public/Group.vue', [
            'group' => $group,
            'leagues' => $leagues,
        ]);
    }
}
