<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('local')) {
            $userIds = User::all()->pluck('id')->toArray();

            for ($i = 0; $i < 50; $i++) {
                Customer::factory()->create([
                    'created_by' => fake()->randomElement($userIds),
                ]);
            }
        }
    }
}
