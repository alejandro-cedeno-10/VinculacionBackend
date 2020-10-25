<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReporteEstudiantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reporte_estudiantes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->char('idEstudiante',10);

            $table->timestamps();

            $table->foreign('idEstudiante')->references('idEstudiante')->on('estudiantes')->onDelete('cascade')->onUpdate('cascade');            
            
            $table->foreignId('idAnomalia');
            $table->foreign('idAnomalia')->references('idAnomalia')->on('anomalias')->onDelete('cascade')->onUpdate('cascade');            
            
            $table->primary(['idEstudiante','idAnomalia']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reporte_estudiantes');
    }
}
