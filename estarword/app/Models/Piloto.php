<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piloto extends Model
{
    /** @use HasFactory<\Database\Factories\PilotoFactory> */
    use HasFactory;

    protected $table = 'pilotos';

    protected $fillable = [
        'nombre',
        'altura',
        'anio_nacimiento',
        'genero',
        'imagen_piloto',
    ];

    // RELACIÓN: Un Piloto puede pilotar varias Naves Estelares (Muchos a Muchos)
    public function navesEstelares()
    {
        // Se define la tabla pivote y se incluyen las columnas extra
        return $this->belongsToMany(NaveEstelar::class, 'piloto_nave')
                    ->withPivot('fecha_inicio', 'fecha_fin')
                    ->withTimestamps();
    }
}
