<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Planeta;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Planeta>
 */
class PlanetaFactory extends Factory
{
    protected $model = Planeta::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->word,
            'periodo_rotacion' => $this->faker->word,
            'poblacion' => $this->faker->numberBetween(1, 1000000),
            'clima' => $this->faker->word,
        ];
    }
}
