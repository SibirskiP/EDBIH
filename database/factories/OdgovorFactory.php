<?php

namespace Database\Factories;

use App\Models\Komentar;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Odgovor>
 */
class OdgovorFactory extends Factory
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
            'sadrzaj' => $this->faker->sentence(),
            'user_id'=>User::factory(),
            'komentar_id'=>Komentar::factory(),

        ];
    }
}
