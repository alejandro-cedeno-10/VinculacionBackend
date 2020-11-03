<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class matricula extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // Nombre de la tabla en MySQL.
    protected $table='matriculas';
    
    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
	// Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
	protected $primaryKey = 'idMatricula';
    
    
    // Atributos que se pueden asignar de manera masiva.
    protected $fillable = [
        'idMatricula','idRepresentante','idCurso','idParalelo','idEspecialidad','idEstudiante',
        'idPeriodoLectivo','folder'
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
    
    public function Estados()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\estado','estado_estudiantes','idMatricula','idEstado')
                ->withPivot('fecha','idEstudiante','descripcion');
    }
    
    public function EstudiantesPivote()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\estudiante','estado_estudiantes','idMatricula','idEstudiante')
                ->withPivot('fecha','idEstado','descripcion');
    }
    
    public function Estudiante()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsTo('App\estudiante','idEstudiante','idEstudiante');
    }
    
    public function Representante()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsTo('App\representante','idRepresentante','idRepresentante');
    }
    
    public function Curso()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsTo('App\curso','idCurso','idCurso');
    }

    public function Paralelo()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsTo('App\paralelo','idParalelo','idParalelo');
    }

    public function Especialidad()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsTo('App\especialidad','idEspecialidad','idEspecialidad');
    }

    public function PeriodoLectivo()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsTo('App\periodo_lectivo','idPeriodoLectivo','idPeriodoLectivo');
    }
}
