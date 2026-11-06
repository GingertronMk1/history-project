<?php

namespace App\Http\Controllers;

use App\Models\Year;
use Inertia\Inertia;
use Inertia\Response;

class YearController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('years/Index', [
            'years' => Year::query()->withCount('shows')->paginate(24),
        ]);
    }

    public function show(Year $year): Response
    {
        return Inertia::render('years/Show', [
            'year' => $year,
            'shows' => $year->shows()->orderBy('title')->orderBy('id')->paginate(24),
        ]);
    }
}
