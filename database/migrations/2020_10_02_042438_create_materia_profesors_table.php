<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMateriaProfesorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materia_profesors', function (Blueprint $table) {
            $table->id('idMateriaProfesor');
            $table->char('idProfesor',10);
            $table->integer('numeroHoras');
            
            $table->timestamps();
            
            $table->foreign('idProfesor')->references('idProfesor')->on('profesors')->onDelete('cascade')->onUpdate('cascade');
            
            $table->foreignId('idCurso');
            $table->foreign('idCurso')->references('idCurso')->on('cursos')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('idParalelo');
            $table->foreign('idParalelo')->references('idParalelo')->on('paralelos')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('idEspecialidad');
            $table->foreign('idEspecialidad')->references('idEspecialidad')->on('especialidads')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('idPeriodoLectivo');
            $table->foreign('idPeriodoLectivo')->references('idPeriodoLectivo')->on('periodo_lectivos')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('idMateria');
            $table->foreign('idMateria')->references('idMateria')->on('materias')->onDelete('cascade')->onUpdate('cascade');

            $table->primary('idMateriaProfesor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materia_profesors');
    }
}
