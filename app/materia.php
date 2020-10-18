<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class materia extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // Nombre de la tabla en MySQL.
    protected $table='materias';
    
    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
	// Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
	protected $primaryKey = 'idMateria';
    
    // Atributos que se pueden asignar de manera masiva.
    protected $fillable = [
        'idTipoAsignatura','nombreMateria'
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
    
    public function Profesor()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\profesor','materia_profesors','idMateria','idProfesor')
                ->withPivot('idMateriaProfesor','idCurso','idParalelo','idEspecialidad',
                'idPeriodoLectivo','numeroHoras');
    }

    public function Curso()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\curso','materia_profesors','idMateria','idCurso')
                ->withPivot('idMateriaProfesor','idProfesor','idParalelo','idEspecialidad',
                'idPeriodoLectivo','numeroHoras');
    }

    public function Paralelo()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\paralelo','materia_profesors','idMateria','idParalelo')
                ->withPivot('idMateriaProfesor','idProfesor','idCurso','idEspecialidad',
                'idPeriodoLectivo','numeroHoras');
    }

    public function Especialidad()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\especialidad','materia_profesors','idMateria','idEspecialidad')
                ->withPivot('idMateriaProfesor','idCurso','idParalelo','idProfesor',
                'idPeriodoLectivo','numeroHoras');
    }

    public function Periodo_Lectivo()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\periodo_lectivo','materia_profesors','idMateria','idPeriodoLectivo')
                ->withPivot('idMateriaProfesor','idCurso','idParalelo','idEspecialidad',
                'idProfesor','numeroHoras');
    }
    public function Tipo_Asignatura()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsTo('App\tipo_asignatura','idTipoAsignatura','idTipoAsignatura');
    }
}
