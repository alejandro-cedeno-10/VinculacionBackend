<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class profesor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // Nombre de la tabla en MySQL.
    protected $table='profesors';
    
    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
	// Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
	protected $primaryKey = 'idProfesor';

    // Para indicar que no hay autoincremento en la clave primaria
    public $incrementing = false;

    // Como no se hara operaciones con el numero de cedula
    // indicamos que es string
    protected $keyType = 'string';
    
    
    // Atributos que se pueden asignar de manera masiva.
    protected $fillable = [
        'idProfesor','cargo','titulacion','fechaIngreso'
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
    
    public function User()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsTo('App\User','idProfesor','idPersona');
    }
    
    public function Materias()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\materia','materia_profesors','idProfesor','idMateria')
                ->withPivot('idMateriaProfesor','idCurso','idParalelo','idEspecialidad',
                'idPeriodoLectivo','numeroHoras');
    }
    
    public function Cursos()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\curso','materia_profesors','idProfesor','idCurso')
                ->withPivot('idMateriaProfesor','idMateria','idParalelo','idEspecialidad',
                'idPeriodoLectivo','numeroHoras');
    }

    public function Paralelos()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\paralelo','materia_profesors','idProfesor','idParalelo')
                ->withPivot('idMateriaProfesor','idMateria','idCurso','idEspecialidad',
                'idPeriodoLectivo','numeroHoras');
    }

    public function Especialidades()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\especialidad','materia_profesors','idProfesor','idEspecialidad')
                ->withPivot('idMateriaProfesor','idCurso','idParalelo','idMateria',
                'idPeriodoLectivo','numeroHoras');
    }

    public function PeriodoLectivos()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\periodo_lectivo','materia_profesors','idProfesor','idPeriodoLectivo')
                ->withPivot('idMateriaProfesor','idCurso','idParalelo','idEspecialidad',
                'idMateria','numeroHoras');
    }

    public function Anomalias()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\anomalia','diagnostico_tutors','idProfesor','idAnomalia')
                ->withPivot('descripcion');
    }
}
