<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Http\Requests\JobApplicationRequest;
use App\Models\JobApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $applications = $request->user()
            ->jobApplications()
            ->with('company')
            ->latest()
            ->get();

        return view('applications.index', ['applications' => $applications]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        return view('applications.create', [
            'application' => new JobApplication(['status' => ApplicationStatus::Wishlist]),
            'companies' => $request->user()->companies()->orderBy('name')->get(),
            'statuses' => ApplicationStatus::cases(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JobApplicationRequest $request): RedirectResponse
    {
        $company = $request->user()
            ->companies()
            ->findOrFail($request->validated('company_id'));

        $application = $company->jobApplications()->create($request->validated());

        return redirect()
            ->route('applications.show', $application)
            ->with('status', 'Application added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobApplication $application): View
    {
        $this->authorize('view', $application);

        $application->load('company');

        return view('applications.show', ['application' => $application]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, JobApplication $application): View
    {
        $this->authorize('update', $application);

        return view('applications.edit', [
            'application' => $application,
            'companies' => $request->user()->companies()->orderBy('name')->get(),
            'statuses' => ApplicationStatus::cases(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobApplicationRequest $request, JobApplication $application): RedirectResponse
    {
        $this->authorize('update', $application);

        $request->user()
            ->companies()
            ->findOrFail($request->validated('company_id'));

        $application->update($request->validated());

        return redirect()
            ->route('applications.show', $application)
            ->with('status', 'Application updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobApplication $application): RedirectResponse
    {
        $this->authorize('delete', $application);

        $application->delete();

        return redirect()
            ->route('applications.index')
            ->with('status', 'Application deleted.');
    }
}
