<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $paymentMethods = [
            'Efectivo',
        ];

        if (app()->environment('local')) {
            $paymentMethods = array_merge($paymentMethods, [
                'Bancolombia',
                'Nequi',
                'Transferencia',
            ]);
        }

        foreach ($paymentMethods as $paymentMethod) {
            PaymentMethod::create([
                'name' => $paymentMethod,
                'is_digital' => in_array($paymentMethod, ['Bancolombia', 'Nequi', 'Daviplata', 'Transferencia']),
                'created_by' => 1,
            ]);
        }
    }
}
