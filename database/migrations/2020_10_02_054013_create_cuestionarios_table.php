<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuestionariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuestionarios', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('idCuestionario');
            $table->char('idPersona',10);
            
            $table->timestamps();

            $table->foreign('idPersona')->references('idPersona')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('idCurso');
            $table->foreign('idCurso')->references('idCurso')->on('cursos')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('idParalelo');
            $table->foreign('idParalelo')->references('idParalelo')->on('paralelos')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('idEspecialidad');
            $table->foreign('idEspecialidad')->references('idEspecialidad')->on('especialidads')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('idPeriodoLectivo');
            $table->foreign('idPeriodoLectivo')->references('idPeriodoLectivo')->on('periodo_lectivos')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuestionarios');
    }
}
