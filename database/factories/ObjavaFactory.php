<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Objava>
 */
class ObjavaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'kategorija' => $this->faker->randomElement(['osnovna skola', 'srednja skola', 'fakultet', 'jezici', 'ostalo']),

            'naziv' => $this->faker->name(),
            'sadrzaj' => $this->faker->text(),
            'putanja'=>null,
            'user_id'=>User::factory(),
        ];
    }
}
