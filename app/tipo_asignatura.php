<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tipo_asignatura extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // Nombre de la tabla en MySQL.
    protected $table='tipo_asignaturas';
    
    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
	// Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
	protected $primaryKey = 'idTipoAsignatura';    
    
    // Atributos que se pueden asignar de manera masiva.
    protected $fillable = [
        'nombreTipoAsignatura'
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
    
    
    public function materias()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->hasMany('App\materia','idTipoAsignatura','idTipoAsignatura');
    }
}
