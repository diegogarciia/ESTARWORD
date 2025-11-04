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

    public function planeta()
    {
        return $this->belongsTo(Planeta::class, 'id');
    }

    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class, 'id_nave_estelar');
    }

    public function pilotos()
    {
        return $this->belongsToMany(Piloto::class, 'piloto_nave', 'id_nave_estelar', 'id_piloto')
                    ->withPivot('fecha_inicio', 'fecha_fin')
                    ->withTimestamps();
    }
}
