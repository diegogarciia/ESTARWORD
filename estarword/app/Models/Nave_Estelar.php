<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nave_Estelar extends Model
{
    /** @use HasFactory<\Database\Factories\NaveEstelarFactory> */
    use HasFactory;

    protected $table = 'naves_estelares';

    protected $fillable = [
        'nombre',
        'modelo',
        'tripulacion',
        'pasajeros',
        'clase_nave',
        'id_planeta',
        'id_piloto',
    ];

    // RELACIÓN: Una Nave Estelar pertenece a un Planeta (Muchos a Uno)
    public function planeta()
    {
        return $this->belongsTo(Planeta::class, 'id');
    }

    // RELACIÓN: Una Nave Estelar tiene muchos Mantenimientos (Uno a Muchos)
    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class, 'id_nave_estelar');
    }

    // RELACIÓN: Una Nave Estelar tiene varios Pilotos (Muchos a Muchos)
    public function pilotos()
    {
        // Se define la tabla pivote y se incluyen las columnas extra
        return $this->belongsToMany(Piloto::class, 'piloto_nave', 'id_nave_estelar', 'id_piloto')
                    ->withPivot('fecha_inicio', 'fecha_fin')
                    ->withTimestamps();
    }
}
