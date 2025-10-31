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
    ];

    // RELACIÓN: Una Nave Estelar pertenece a un Planeta (Muchos a Uno)
    public function planeta()
    {
        return $this->belongsTo(Planeta::class);
    }

    // RELACIÓN: Una Nave Estelar tiene muchos Mantenimientos (Uno a Muchos)
    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class);
    }

    // RELACIÓN: Una Nave Estelar tiene varios Pilotos (Muchos a Muchos)
    public function pilotos()
    {
        // Se define la tabla pivote y se incluyen las columnas extra
        return $this->belongsToMany(Piloto::class, 'piloto_nave')
                    ->withPivot('fecha_inicio', 'fecha_fin')
                    ->withTimestamps();
    }
}
