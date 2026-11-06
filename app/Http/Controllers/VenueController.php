<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Inertia\Inertia;
use Inertia\Response;

class VenueController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('venues/Index', [
            'venues' => Venue::query()->withCount('shows')->orderBy('name')->orderBy('id')->paginate(24),
        ]);
    }

    public function show(Venue $venue): Response
    {
        return Inertia::render('venues/Show', [
            'venue' => $venue,
            'shows' => $venue->shows()->orderBy('title')->orderBy('id')->paginate(24),
        ]);
    }
}
