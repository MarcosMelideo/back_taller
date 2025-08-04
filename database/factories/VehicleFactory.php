<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
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
            'brand' =>  $faker->vehicleBrand(),
            'model' =>  $faker->vehicleModel(),
            'year' =>  $this->faker->biasedNumberBetween(1990, date('Y'), 'sqrt'),
            'patent' =>  $faker->vehicleRegistration(),
            'mileage' =>  $this->faker->randomNumber(),
            'client_name' => $this->faker->firstname(),
            'client_id' => $this->faker->numberBetween($min = 1, $max = 50),
            'register_date' => $this->faker->date(),
        ];
    }
}
