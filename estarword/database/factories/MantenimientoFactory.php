<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Mantenimiento;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mantenimiento>
 */
class MantenimientoFactory extends Factory
{
    protected $model = Mantenimiento::class;

    public function definition(): array
    {
        return [
            'id_nave_estelar' => Nave_Estelar::factory(),
            'fecha_mantenimiento' => $this->faker->date(),
            'descripcion' => $this->faker->text(),
            'coste' => $this->faker->randomFloat(2, 1000, 10000),
        ];
    }
}
