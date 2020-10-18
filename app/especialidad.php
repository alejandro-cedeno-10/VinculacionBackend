<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class especialidad extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // Nombre de la tabla en MySQL.
    protected $table='especialidads';
    
    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
	// Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
	protected $primaryKey = 'idEspecialidad';    
    
    // Atributos que se pueden asignar de manera masiva.
    protected $fillable = [
        'especialidad'
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
        return $this->belongsToMany('App\profesor','materia_profesors','idEspecialidad','idProfesor')
                ->withPivot('idMateriaProfesor','idMateria','idParalelo','idCurso',
                'idPeriodoLectivo','numeroHoras');
    }

    public function Materia()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\materia','materia_profesors','idEspecialidad','idMateria')
                ->withPivot('idMateriaProfesor','idProfesor','idParalelo','idCurso',
                'idPeriodoLectivo','numeroHoras');
    }

    public function Curso()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\curso','materia_profesors','idEspecialidad','idCurso')
                ->withPivot('idMateriaProfesor','idProfesor','idParalelo','idMateria',
                'idPeriodoLectivo','numeroHoras');
    }
    
    public function Paralelo()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\paralelo','materia_profesors','idEspecialidad','idParalelo')
                ->withPivot('idMateriaProfesor','idProfesor','idMateria','idCurso',
                'idPeriodoLectivo','numeroHoras');
    }

    public function Periodo_Lectivo()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\periodo_lectivo','materia_profesors','idEspecialidad','idPeriodoLectivo')
                ->withPivot('idMateriaProfesor','idProfesor','idParalelo','idCurso',
                'idMateria','numeroHoras');
    }

    public function Matricula()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->hasMany('App\matricula','idEspecialidad','idEspecialidad');
    }

    public function Cuestionario()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->hasMany('App\cuestionario','idEspecialidad','idEspecialidad');
    }
}
