<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatriculasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->char('idMatricula',10);
            $table->char('idRepresentante',10);
            $table->char('idEstudiante',10);
            $table->string('folder');
            
            $table->timestamps();
            
            $table->foreign('idRepresentante')->references('idRepresentante')->on('representantes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('idEstudiante')->references('idEstudiante')->on('estudiantes')->onDelete('cascade')->onUpdate('cascade');
            
            $table->foreignId('idCurso');
            $table->foreign('idCurso')->references('idCurso')->on('cursos')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('idParalelo');
            $table->foreign('idParalelo')->references('idParalelo')->on('paralelos')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('idEspecialidad');
            $table->foreign('idEspecialidad')->references('idEspecialidad')->on('especialidads')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('idPeriodoLectivo');
            $table->foreign('idPeriodoLectivo')->references('idPeriodoLectivo')->on('periodo_lectivos')->onDelete('cascade')->onUpdate('cascade');
            
            $table->primary('idMatricula');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matriculas');
    }
}
