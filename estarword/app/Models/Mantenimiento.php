<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    /** @use HasFactory<\Database\Factories\MantenimientoFactory> */
    use HasFactory;

    protected $table = 'mantenimientos';

    protected $fillable = [
        'id_nave_estelar',
        'fecha_mantenimiento',
        'descripcion',
        'coste',
    ];

    public function naveEstelar()
    {
        return $this->belongsTo(NaveEstelar::class, 'id_nave_estelar');
    }
    
}
