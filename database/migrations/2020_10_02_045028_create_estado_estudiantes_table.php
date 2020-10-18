<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadoEstudiantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estado_estudiantes', function (Blueprint $table) {
            $table->char('idEstudiante',10);
            $table->date('fecha');
            $table->char('idMatricula',10);
            $table->string('descripcion');
                        
            $table->timestamps();

            $table->foreign('idEstudiante')->references('idEstudiante')->on('estudiantes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('idMatricula')->references('idMatricula')->on('matriculas')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('idEstado');
            $table->foreign('idEstado')->references('idEstado')->on('estados')->onDelete('cascade')->onUpdate('cascade');

            $table->primary(['idEstudiante','fecha']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estado_estudiantes');
    }
}
