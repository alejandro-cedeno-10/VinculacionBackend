<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->char('idPersona',10);
            $table->string('apellidoPaterno');
            $table->string('apellidoMaterno');
            $table->string('nombres');
            $table->string('direccion');
            $table->string('telefono')->nullable();
            $table->enum('sexo', ['M', 'F']);
            $table->date('fechaNacimiento');
            $table->enum('estadoCivil', ['S', 'C', 'V', 'D', 'U']);
            
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->boolean('active')->default(false);
            $table->string('activation_token')->nullable();
            $table->string('avatar')->default('default.jpg');       
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('idPersona');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
