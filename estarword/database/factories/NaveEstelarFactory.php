<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Nave_Estelar;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Nave_Estelar>
 */
class NaveEstelarFactory extends Factory
{
    
    protected $model = Nave_Estelar::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->word,
            'modelo' => $this->faker->word,
            'tripulacion' => $this->faker->numberBetween(1, 100),
            'pasajeros' => $this->faker->numberBetween(1, 1000),
            'clase_nave' => $this->faker->word,
        ];
    }
}
