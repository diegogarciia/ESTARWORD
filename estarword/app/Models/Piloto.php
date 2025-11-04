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

    public function navesEstelares()
    {
        return $this->belongsToMany(NaveEstelar::class, 'piloto_nave', 'id_piloto', 'id_nave_estelar')
                    ->withPivot('fecha_inicio', 'fecha_fin')
                    ->withTimestamps();
    }
}
