<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfesorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profesors', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->char('idProfesor',10);
            $table->string('cargo');
            $table->string('titulacion');
            $table->date('fechaIngreso');
                        
            $table->timestamps();

            $table->foreign('idProfesor')->references('idPersona')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->primary('idProfesor');                       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profesors');
    }
}
