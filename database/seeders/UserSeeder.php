<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::create([
            'identification_number' => '0000000000',
            'full_name' => 'Super Admin',
            'phone' => '0000000000',
            'email' => 'super_admin@gmail.com',
            'birth_date' => now(),
            'email_verified_at' => now(),
            'password' => app()->environment('local')
                ? Hash::make('admin')
                : Hash::make('B0ilerplate@' . now()->year()),
        ]);

        if (app()->environment('local')) {
            User::create([
                'identification_number' => '111111111',
                'full_name' => 'Test Test',
                'phone' => '111111111',
                'email' => 'test@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('test'),
                'created_by' => $superAdmin->id,
            ]);

            User::factory(10)->create([
                'created_by' => $superAdmin->id,
            ]);
        }
    }
}
