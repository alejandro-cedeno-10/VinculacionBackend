<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class reporte_estudiante extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // Nombre de la tabla en MySQL.
    protected $table='reporte_estudiantes';
    
    // Atributos que se pueden asignar de manera masiva.
    protected $fillable = [
        'idEstudiante','idAnomalia'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    // Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
    protected $hidden = [
        'created_at','updated_at'
    ];
}
