<?php

namespace App\Http\Controllers;

use App\Models\Season;
use Inertia\Inertia;
use Inertia\Response;

class SeasonController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('seasons/Index', [
            'seasons' => Season::query()->withCount('shows')->orderBy('name')->orderBy('id')->paginate(24),
        ]);
    }

    public function show(Season $season): Response
    {
        return Inertia::render('seasons/Show', [
            'season' => $season,
            'shows' => $season->shows()->orderBy('title')->orderBy('id')->paginate(24),
        ]);
    }
}
