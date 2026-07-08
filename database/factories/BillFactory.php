<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bill>
 */
class BillFactory extends Factory
{
    protected $model = Bill::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customer = Customer::inRandomOrder()->first();

        return [
            'customer_id' => $customer ? $customer->id : Customer::factory(),
            'billing_year' => $this->faker->randomElement(['2025', '2026']),
            'billing_month' => $this->faker->numberBetween(1, 12),
            'electricity_usage' => $this->faker->numberBetween(50, 600),
        ];
    }
}
