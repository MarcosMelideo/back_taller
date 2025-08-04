<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paymentitem>
 */
class PaymentitemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker = \Faker\Factory::create('es_ES');
        return [
            'quantity' => $this->faker->randomDigitNotNull(),
            'description' => $this->faker->sentence(), 
            'price_by_unit' => $this->faker->randomNumber(2), 
            'subtotal' => $this->faker->randomNumber(2),
            'payment_id' => $this->faker->numberBetween($min = 1, $max = 50)
        ];
    }
}
