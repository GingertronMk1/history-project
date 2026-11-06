<?php

namespace App\Http\Controllers;

use App\Models\Show;
use Inertia\Inertia;
use Inertia\Response;

class ShowController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('shows/Index', [
            'shows' => Show::query()->orderBy('title')->orderBy('id')->paginate(24),
        ]);
    }

    public function show(Show $show): Response
    {
        return Inertia::render('shows/Show', [
            'show' => $show->load(['year', 'company', 'season', 'period', 'playwright', 'venue']),
        ]);
    }
}
