<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Company::factory()
            ->count(10)
            ->for($user)
            ->has(
                JobApplication::factory()
                    ->count(3)
                    ->sequence(
                        ['status' => ApplicationStatus::Wishlist],
                        ['status' => ApplicationStatus::Applied, 'applied_on' => now()->subDays(5)],
                        ['status' => ApplicationStatus::Interviewing, 'applied_on' => now()->subDays(12)],
                    ),
            )
            ->create();
    }
}
