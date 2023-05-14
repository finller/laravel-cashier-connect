<?php

namespace Laravel\Cashier\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laravel\Cashier\CashierCustomer;

class CashierCustomerFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CashierCustomer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 
        ];
    }

    public function chargeableConnect(): static
    {
        return $this->state([
            'account_details' => [
                'capabilities' => [
                    'transfers' => 'active',
                ],
                'charges_enabled' => true,
                'payouts_enabled' => true,
                'details_submitted' => true,
            ]
        ]);
    }
}
