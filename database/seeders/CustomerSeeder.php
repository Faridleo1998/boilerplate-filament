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

            Customer::factory(50)->create([
                'created_by' => fake()->randomElement($userIds),
            ]);
        }
    }
}
