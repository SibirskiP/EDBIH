<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Materijal>
 */
class MaterijalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //

            'naziv'=>$this->faker->word(),
            'opis'=>$this->faker->text(),
            'kategorija' => $this->faker->randomElement(['osnovna skola', 'srednja skola', 'fakultet', 'jezici', 'ostalo']),
            'putanja'=>null,
            'user_id'=>User::factory(),

        ];
    }
}
