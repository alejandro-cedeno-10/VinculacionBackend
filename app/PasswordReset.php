<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    //
    protected $table='password_resets';

    // Atributos que se pueden asignar de manera masiva.
    protected $fillable = [
        'email', 'token'
    ];

    
}
