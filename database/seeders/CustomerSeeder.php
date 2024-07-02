<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('local')) {
            Customer::factory(10)->create();
        }
    }
}
