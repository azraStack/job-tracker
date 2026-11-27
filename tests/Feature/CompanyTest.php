<?php

use App\Models\Company;
use App\Models\User;

it('redirects guests from the companies page to login', function () {
    $this->get(route('companies.index'))
        ->assertRedirect(route('login'));
});

it('lists only the authenticated user companies', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $company = Company::factory()->for($user)->create(['name' => 'My Company']);
    $otherCompany = Company::factory()->for($otherUser)->create(['name' => 'Their Company']);

    $this->actingAs($user)
        ->get(route('companies.index'))
        ->assertOk()
        ->assertSee($company->name)
        ->assertDontSee($otherCompany->name);
});

it('forbids viewing another user company', function () {
    $user = User::factory()->create();
    $otherCompany = Company::factory()->for(User::factory())->create();

    $this->actingAs($user)
        ->get(route('companies.show', $otherCompany))
        ->assertForbidden();
});

it('creates a company for the authenticated user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('companies.store'), [
            'name' => 'Acme',
            'website' => 'https://acme.test',
            'location' => 'Remote',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('companies', [
        'user_id' => $user->id,
        'name' => 'Acme',
    ]);
});

it('allows the same company name for different users', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Company::factory()->for($otherUser)->create(['name' => 'Acme']);

    $this->actingAs($user)
        ->post(route('companies.store'), ['name' => 'Acme'])
        ->assertSessionHasNoErrors();

    expect($user->companies()->where('name', 'Acme')->exists())->toBeTrue();
});

it('rejects a duplicate company name for the same user', function () {
    $user = User::factory()->create();

    Company::factory()->for($user)->create(['name' => 'Acme']);

    $this->actingAs($user)
        ->post(route('companies.store'), ['name' => 'Acme'])
        ->assertSessionHasErrors('name');
});
