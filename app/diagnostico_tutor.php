<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class diagnostico_tutor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // Nombre de la tabla en MySQL.
    protected $table='diagnostico_tutors';
    
    // Atributos que se pueden asignar de manera masiva.
    protected $fillable = [
        'idAnomalia','idProfesor','descripcion'
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
