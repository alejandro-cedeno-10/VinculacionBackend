<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuestionarioPreguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuestionario_preguntas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->foreignId('idCuestionario');
            $table->foreign('idCuestionario')->references('idCuestionario')->on('cuestionarios')->onDelete('cascade')->onUpdate('cascade');

            $table->foreignId('idPregunta');
            $table->foreign('idPregunta')->references('idPregunta')->on('preguntas')->onDelete('cascade')->onUpdate('cascade');
            
            $table->timestamps();

            $table->primary(['idCuestionario', 'idPregunta']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuestionario_preguntas');
    }
}
