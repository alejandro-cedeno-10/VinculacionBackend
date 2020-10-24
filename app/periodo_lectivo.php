<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class periodo_lectivo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // Nombre de la tabla en MySQL.
    protected $table='periodo_lectivos';
    
    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
	// Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
	protected $primaryKey = 'idPeriodoLectivo';    
    
    // Atributos que se pueden asignar de manera masiva.
    protected $fillable = [
        'fechaInicio','fechaFinal','periodoLectivo'
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
    
    public function Profesores()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\profesor','materia_profesors','idPeriodoLectivo','idProfesor')
                ->withPivot('idMateriaProfesor','idMateria','idParalelo','idCurso',
                'idEspecialidad','numeroHoras');
    }

    public function Materias()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\materia','materia_profesors','idPeriodoLectivo','idMateria')
                ->withPivot('idMateriaProfesor','idProfesor','idParalelo','idCurso',
                'idEspecialidad','numeroHoras');
    }

    public function Cursos()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\curso','materia_profesors','idPeriodoLectivo','idCurso')
                ->withPivot('idMateriaProfesor','idProfesor','idParalelo','idEspecialidad',
                'idMateria','numeroHoras');
    }
    public function Paralelos()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\paralelo','materia_profesors','idPeriodoLectivo','idParalelo')
                ->withPivot('idMateriaProfesor','idProfesor','idMateria','idEspecialidad',
                'idCurso','numeroHoras');
    }

    public function Especialidades()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\especialidad','materia_profesors','idPeriodoLectivo','idEspecialidad')
                ->withPivot('idMateriaProfesor','idProfesor','idParalelo','idMateria',
                'idCurso','numeroHoras');
    }

    public function Matriculas()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->hasMany('App\matricula','idPeriodoLectivo','idPeriodoLectivo');
    }

    public function Cuestionarios()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->hasMany('App\cuestionario','idPeriodoLectivo','idPeriodoLectivo');
    }

    public function Cuerpos_dece()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->belongsToMany('App\cuerpo_dece','dece_lectivos','idPeriodoLectivo','idPersona');
    }
}
