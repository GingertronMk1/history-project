<?php

namespace App\Http\Controllers;

use App\Models\Playwright;
use Inertia\Inertia;
use Inertia\Response;

class PlaywrightController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('playwrights/Index', [
            'playwrights' => Playwright::query()->withCount('shows')->orderBy('name')->orderBy('id')->paginate(24),
        ]);
    }

    public function show(Playwright $playwright): Response
    {
        return Inertia::render('playwrights/Show', [
            'playwright' => $playwright,
            'shows' => $playwright->shows()->orderBy('title')->orderBy('id')->paginate(24),
        ]);
    }
}
