<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobApplicationRequest;
use App\Models\JobApplication;
use App\Enums\ApplicationStatus;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $applications = JobApplication::with('company')->latest()->get();

        return view('applications.index', ['applications' => $applications]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('applications.create', [
            'application' => new JobApplication(['status' => ApplicationStatus::Wishlist]),
            'companies' => Company::orderBy('name')->get(),
            'statuses' => ApplicationStatus::cases(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobApplicationRequest $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'role_title' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::enum(ApplicationStatus::class)],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'salary_max' => ['nullable', 'integer', 'gte:salary_min'],
            'applied_on' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $application = JobApplication::create($validated);

        return redirect()
            ->route('applications.show', $application)
            ->with('status', 'Application added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobApplication $application): View
    {
        return view('applications.show', ['application' => $application]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobApplication $application): View
    {
        return view('applications.edit', [
            'application' => $application,
            'companies' => Company::orderBy('name')->get(),
            'statuses' => ApplicationStatus::cases(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobApplication $application): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'role_title' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::enum(ApplicationStatus::class)],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'salary_max' => ['nullable', 'integer', 'gte:salary_min'],
            'applied_on' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $application->update($validated);

        return redirect()
            ->route('applications.show', $application)
            ->with('status', 'Application updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobApplication $application): RedirectResponse
    {
        $application->delete();

        return redirect()
            ->route('applications.index')
            ->with('status', 'Application deleted.');
    }
}
