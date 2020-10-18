<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class curso extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // Nombre de la tabla en MySQL.
    protected $table='cursos';
    
    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
	// Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
	protected $primaryKey = 'idCurso';    
    
    // Atributos que se pueden asignar de manera masiva.
    protected $fillable = [
        'curso'
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
        return $this->belongsToMany('App\profesor','materia_profesors','idCurso','idProfesor')
                ->withPivot('idMateriaProfesor','idMateria','idParalelo','idEspecialidad',
                'idPeriodoLectivo','numeroHoras');
    }

    public function Materia()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\materia','materia_profesors','idCurso','idMateria')
                ->withPivot('idMateriaProfesor','idProfesor','idParalelo','idEspecialidad',
                'idPeriodoLectivo','numeroHoras');
    }

    public function Paralelo()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\paralelo','materia_profesors','idCurso','idParalelo')
                ->withPivot('idMateriaProfesor','idProfesor','idMateria','idEspecialidad',
                'idPeriodoLectivo','numeroHoras');
    }

    public function Especialidad()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\especialidad','materia_profesors','idCurso','idEspecialidad')
                ->withPivot('idMateriaProfesor','idProfesor','idParalelo','idMateria',
                'idPeriodoLectivo','numeroHoras');
    }

    public function Periodo_Lectivo()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\periodo_lectivo','materia_profesors','idCurso','idPeriodoLectivo')
                ->withPivot('idMateriaProfesor','idProfesor','idParalelo','idEspecialidad',
                'idMateria','numeroHoras');
    }

    public function Matricula()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->hasMany('App\matricula','idCurso','idCurso');
    }

    public function Cuestionario()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->hasMany('App\cuestionario','idCurso','idCurso');
    }
}
