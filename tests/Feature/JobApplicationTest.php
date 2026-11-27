<?php

use App\Enums\ApplicationStatus;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows a job application with its company', function () {
    $user = User::factory()->create();
    $company = Company::factory()->for($user)->create();
    $application = JobApplication::factory()->for($company)->create();

    $this->actingAs($user)
        ->get(route('applications.show', $application))
        ->assertOk()
        ->assertSee($application->role_title)
        ->assertSee($company->name);
});

it('counts the job applications belonging to a company', function () {
    $company = Company::factory()->create();
    JobApplication::factory()->count(2)->for($company)->create();

    expect($company->jobApplications()->count())->toBe(2);
});

it('redirects guests from the applications page to login', function () {
    $this->get(route('applications.index'))
        ->assertRedirect(route('login'));
});

it('lists only the authenticated user applications', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $application = JobApplication::factory()
        ->for(Company::factory()->for($user))
        ->create(['role_title' => 'My Application']);

    $otherApplication = JobApplication::factory()
        ->for(Company::factory()->for($otherUser))
        ->create(['role_title' => 'Their Application']);

    $this->actingAs($user)
        ->get(route('applications.index'))
        ->assertOk()
        ->assertSee($application->role_title)
        ->assertDontSee($otherApplication->role_title);
});

it('forbids viewing another user application', function () {
    $user = User::factory()->create();
    $otherApplication = JobApplication::factory()
        ->for(Company::factory()->for(User::factory()))
        ->create();

    $this->actingAs($user)
        ->get(route('applications.show', $otherApplication))
        ->assertForbidden();
});

it('creates an application for a company owned by the user', function () {
    $user = User::factory()->create();
    $company = Company::factory()->for($user)->create();

    $this->actingAs($user)
        ->post(route('applications.store'), [
            'company_id' => $company->id,
            'role_title' => 'Backend Developer',
            'status' => ApplicationStatus::Applied->value,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('job_applications', [
        'company_id' => $company->id,
        'role_title' => 'Backend Developer',
    ]);
});

it('rejects an application for another user company', function () {
    $user = User::factory()->create();
    $otherCompany = Company::factory()->for(User::factory())->create();

    $this->actingAs($user)
        ->post(route('applications.store'), [
            'company_id' => $otherCompany->id,
            'role_title' => 'Backend Developer',
            'status' => ApplicationStatus::Applied->value,
        ])
        ->assertSessionHasErrors('company_id');
});

it('forbids updating another user application', function () {
    $user = User::factory()->create();
    $ownCompany = Company::factory()->for($user)->create();
    $otherApplication = JobApplication::factory()
        ->for(Company::factory()->for(User::factory()))
        ->create();

    $this->actingAs($user)
        ->put(route('applications.update', $otherApplication), [
            'company_id' => $ownCompany->id,
            'role_title' => 'Changed',
            'status' => ApplicationStatus::Applied->value,
        ])
        ->assertForbidden();
});

it('forbids deleting another user application', function () {
    $user = User::factory()->create();
    $otherApplication = JobApplication::factory()
        ->for(Company::factory()->for(User::factory()))
        ->create();

    $this->actingAs($user)
        ->delete(route('applications.destroy', $otherApplication))
        ->assertForbidden();

    $this->assertModelExists($otherApplication);
});
