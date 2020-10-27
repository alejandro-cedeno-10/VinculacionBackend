<?php

namespace App;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // Nombre de la tabla en MySQL.
    protected $table='users';

    protected $guard_name = 'api';
    
    // Eloquent asume que cada tabla tiene una clave primaria con una columna llamada id.
	// Si éste no fuera el caso entonces hay que indicar cuál es nuestra clave primaria en la tabla:
	protected $primaryKey = 'idPersona';

    // Para indicar que no hay autoincremento en la clave primaria
    public $incrementing = false;

    // Como no se hara operaciones con el numero de cedula
    // indicamos que es string
    protected $keyType = 'string';

    
    
    
    // Atributos que se pueden asignar de manera masiva.
    protected $fillable = [
        'idPersona','apellidoPaterno','apellidoMaterno','nombres','direccion','telefono',
        'sexo','fechaNacimiento','estadoCivil','email','password','active', 'activation_token','avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    // Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
    protected $hidden = [
        'password', 'remember_token','activation_token','created_at','updated_at','deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Definimos a continuación la relación de esta tabla con otras.
	// Ejemplos de relaciones:
	// 1 usuario tiene 1 teléfono   ->hasOne() Relación 1:1
	// 1 teléfono pertenece a 1 usuario   ->belongsTo() Relación 1:1 inversa a hasOne()
	// 1 post tiene muchos comentarios  -> hasMany() Relación 1:N 
	// 1 comentario pertenece a 1 post ->belongsTo() Relación 1:N inversa a hasMany()
	// 1 usuario puede tener muchos roles  ->belongsToMany() Relación N:N
    //  etc..
    
    
    public function Estudiante()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->hasOne('App\estudiante','idEstudiante','idPersona');
    }
    
    public function Estudiante_Representante()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->hasOne('App\estudiante','idRepresentante','idPersona');
    }
    public function Profesor()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->hasOne('App\profesor','idProfesor','idPersona');
    }
    
    public function Dece()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->hasOne('App\cuerpo_dece','idPersona','idPersona');
    }
    
    public function Representante()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->hasOne('App\representante','idRepresentante','idPersona');
    }

    public function Mensaje()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->hasOne('App\mensajes','idPersona','idPersona');
    }
    
    public function Mensaje_Receptor()
	{
		// $this hace referencia al objeto que tengamos en ese momento del Usuario
        return $this->hasOne('App\mensajes','receptor','idPersona');
    }
    
}
