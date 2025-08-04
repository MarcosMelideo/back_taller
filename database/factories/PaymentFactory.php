<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = (new \Faker\Factory())::create();
        $faker->addProvider(new \Faker\Provider\FakeCar($faker));
        $this->faker = \Faker\Factory::create('es_ES');
        return [
            'date' => $this->faker->date(),
            'client_name' => $this->faker->firstname(),
            'client_lastname' => $this->faker->lastname(),
            'client_id' => $this->faker->numberBetween($min = 1, $max = 50),
            'brand' =>  $faker->vehicleBrand(),
            'model' => $faker->vehicleModel(),
            'mileage' => $this->faker->randomNumber(),
            'year' => $this->faker->biasedNumberBetween(1990, date('Y'), 'sqrt'),
            'patent' => $faker->vehicleRegistration(),
            'vehicle_id' => $this->faker->numberBetween($min = 1, $max = 50),
            'total' => $this->faker->randomNumber(2),
            'number' => $this->faker->numberBetween($min = 100000, $max = 1000000)
        ];
    }
}
