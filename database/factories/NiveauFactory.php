<?php

namespace Database\Factories;

use App\Models\Filiere;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Niveau>
 */
class NiveauFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label_niveau' => $this->faker->words(2, true),
            'desc_niveau' => $this->faker->paragraph(),
            'code_filiere' => Filiere::inRandomOrder()->first()->code_filiere,
        ];
    }
}
