<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => $this->faker->dateTime($unixTimestamp),
            'postal_code' =>$this->faker->paragraphs,
            'completed' => rand(0,1),
            'user_id' => rand(1,10),
            'created_at' => $this->faker->dateTime($unixTimestamp),
            'updated_at' => $this->faker->dateTime($unixTimestamp),
        ];
    }
}
