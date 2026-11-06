<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Inertia\Inertia;
use Inertia\Response;

class PeriodController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('periods/Index', [
            'periods' => Period::query()->withCount('shows')->orderBy('name')->orderBy('id')->paginate(24),
        ]);
    }

    public function show(Period $period): Response
    {
        return Inertia::render('periods/Show', [
            'period' => $period,
            'shows' => $period->shows()->orderBy('title')->orderBy('id')->paginate(24),
        ]);
    }
}
