<?php

namespace Database\Factories;

use App\Models\Objava;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Komentar>
 */
class KomentarFactory extends Factory
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
            'objava_id'=>Objava::factory(),


        ];
    }
}
