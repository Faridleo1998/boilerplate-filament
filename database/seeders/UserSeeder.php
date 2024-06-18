<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'super_admin@gmail.com',
            'password' => app()->environment('local')
                ? Hash::make('admin')
                : Hash::make("B0ilerplate@{${now()->year()}}*"),
        ]);

        if (app()->environment('local')) {
            User::create([
                'name' => 'Test Test',
                'email' => 'test@gmail.com',
                'password' => Hash::make('test'),
            ]);

            User::factory(10)->create();
        }
    }
}
