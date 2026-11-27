<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $companies = $request->user()
            ->companies()
            ->withCount('jobApplications')
            ->orderBy('name')
            ->get();

        return view('companies.index', ['companies' => $companies]);
    }

    public function create(): View
    {
        return view('companies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('companies', 'name')->where('user_id', $request->user()->id),
            ],
            'website' => ['nullable', 'url:http,https', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $company = $request->user()->companies()->create($validated);

        return redirect()
            ->route('companies.show', $company)
            ->with('status', 'Company added.');
    }

    public function show(Company $company): View
    {
        $this->authorize('view', $company);

        $company->load('jobApplications');

        return view('companies.show', ['company' => $company]);
    }
}
