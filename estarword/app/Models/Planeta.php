<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planeta extends Model
{
    /** @use HasFactory<\Database\Factories\PlanetaFactory> */
    use HasFactory;

    protected $table = 'planetas';

    protected $fillable = [
        'nombre',
        'periodo_rotacion',
        'poblacion',
        'clima',
    ];

    // RELACIÓN: Un Planeta tiene muchas Naves Estelares (Uno a Muchos)
    public function navesEstelares()
    {
        return $this->hasMany(NaveEstelar::class);
    }
    
}
