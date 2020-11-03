<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class estudiante extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // Nombre de la tabla en MySQL.
    protected $table='estudiantes';
    
    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
	// Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
	protected $primaryKey = 'idEstudiante';

    // Para indicar que no hay autoincremento en la clave primaria
    public $incrementing = false;

    // Como no se hara operaciones con el numero de cedula
    // indicamos que es string
    protected $keyType = 'string';
    
    
    // Atributos que se pueden asignar de manera masiva.
    protected $fillable = [
        'idEstudiante','idRepresentante','procedencia'
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


    // Definimos a continuación la relación de esta tabla con otras.
	// Ejemplos de relaciones:
	// 1 usuario tiene 1 teléfono   ->hasOne() Relación 1:1
	// 1 teléfono pertenece a 1 usuario   ->belongsTo() Relación 1:1 inversa a hasOne()
	// 1 post tiene muchos comentarios  -> hasMany() Relación 1:N 
	// 1 comentario pertenece a 1 post ->belongsTo() Relación 1:N inversa a hasMany()
	// 1 usuario puede tener muchos roles  ->belongsToMany()
    //  etc..
    
    
    public function user()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsTo('App\User','idEstudiante','idPersona');
    }
    
    public function UserRepresentante()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsTo('App\User','idRepresentante','idPersona','idRepresentante');
    }
    
    public function Estados()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\estado','estado_estudiantes','idEstudiante','idEstado')
                ->withPivot('fecha','idMatricula','descripcion');
    }
    
    public function MatriculasPivote()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\matricula','estado_estudiantes','idEstudiante','idMatricula')
                ->withPivot('fecha','idEstado','descripcion');
    }
    
    public function Matriculas()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->hasMany('App\matricula','idEstudiante','idEstudiante');
    }

    public function Anomalias()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\anomalia','reporte_estudiantes','idEstudiante','idAnomalia');
    }
}
