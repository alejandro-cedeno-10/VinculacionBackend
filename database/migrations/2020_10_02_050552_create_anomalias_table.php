<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnomaliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anomalias', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id('idAnomalia');
            $table->string('afectado');
            $table->string('descripcion')->nullable();
            $table->enum('valoracion',['Regular', 'Urgente', 'Muy urgente']);
            
            $table->timestamps();

            $table->foreignId('idSubCategoria');
            $table->foreign('idSubCategoria')->references('idSubCategoria')->on('subcategorias')->onDelete('cascade')->onUpdate('cascade');            

            $table->foreignId('idMateriaProfesor');
            $table->foreign('idMateriaProfesor')->references('idMateriaProfesor')->on('materia_Profesors')->onDelete('cascade')->onUpdate('cascade');            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anomalias');
    }
}
