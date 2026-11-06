<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Inertia\Inertia;
use Inertia\Response;

class CompanyController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('companies/Index', [
            'companies' => Company::query()->withCount('shows')->orderBy('name')->orderBy('id')->paginate(24),
        ]);
    }

    public function show(Company $company): Response
    {
        return Inertia::render('companies/Show', [
            'company' => $company,
            'shows' => $company->shows()->orderBy('title')->orderBy('id')->paginate(24),
        ]);
    }
}
