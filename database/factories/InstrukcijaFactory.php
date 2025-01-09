<?php

namespace Database\Factories;

use App\Models\Instruktor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instrukcija>
 */
class InstrukcijaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lokacije = config('mojconfig.lokacije');

        return [
            //

            'user_id'=>User::factory(),

            'kategorija' => $this->faker->randomElement(['osnovna skola', 'srednja skola', 'fakultet', 'jezici', 'ostalo']),
            'vrsta'=>$this->faker->randomElement(['uzivo', 'online', 'uzivo i online']),
            'lokacija'=>$this->faker->randomElement($lokacije),
            'naziv' => $this->faker->company(),
            'cijena' => $this->faker->numberBetween(10, 50),
            'opis' => $this->faker->text(),

        ];
    }
}
