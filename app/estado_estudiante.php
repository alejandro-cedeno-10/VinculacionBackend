<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class estado_estudiante extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // Nombre de la tabla en MySQL.
    protected $table='estado_estudiantes';
    
    protected $primaryKey = null;

    public $incrementing = false;

    // Atributos que se pueden asignar de manera masiva.
    protected $fillable = [
        'idEstudiante','fecha','idEstado','idMatricula','descripcion'
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

    public function estado()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsTo('App\estado','idEstado','idEstado');
    }
}
