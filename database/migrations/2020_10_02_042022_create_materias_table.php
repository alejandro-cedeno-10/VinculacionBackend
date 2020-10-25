<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMateriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materias', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('idMateria');
            $table->string('nombreMateria');
            
            $table->timestamps();

            $table->foreignId('idTipoAsignatura');
            $table->foreign('idTipoAsignatura')->references('idTipoAsignatura')->on('tipo_asignaturas')->onDelete('cascade')->onUpdate('cascade');            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materias');
    }
}
