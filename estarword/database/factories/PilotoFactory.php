<?php

namespace Database\Factories;

use App\Models\Piloto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Piloto>
 */
class PilotoFactory extends Factory
{
    protected $model = Piloto::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->name,
            'altura' => $this->faker->numberBetween(150, 210),
            'anio_nacimiento' => $this->faker->year,
            'genero' => $this->faker->randomElement(['masculino', 'femenino', 'n/a']),
        ];
    }
}
