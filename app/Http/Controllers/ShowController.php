<?php

namespace App\Http\Controllers;

use App\Models\Show;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShowController extends Controller
{
    public function index(Request $request): Response
    {
        $request->validate(['q' => ['nullable', 'string', 'max:200']]);
        $search = trim($request->string('q')->toString());

        return Inertia::render('shows/Index', [
            'shows' => Show::query()
                ->when($search !== '', fn (Builder $query): Builder => $query->whereLike('title', '%'.$search.'%'))
                ->orderBy('title')->orderBy('id')->paginate(24)->withQueryString(),
            'search' => $search,
        ]);
    }

    public function show(Show $show): Response
    {
        return Inertia::render('shows/Show', [
            'show' => $show,
        ]);
    }
}
